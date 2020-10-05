<?php

namespace App\Http\Controllers;

use App\LineItem;
use App\Log;
use App\LoginDetails;
use App\OrderVendor;
use App\Product;
use App\ProductImage;
use App\ProductVendorDetail;
use App\Shop;
use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyProduct;
use App\ShopifyVarient;
use App\Tracking;
use App\User;
use App\Vendor;
use App\Webhook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Diff\Line;

class AdminController extends Controller
{

    public function getProducts(Request $request) {

        if ($request->has('search')) {
            $products = ShopifyProduct::where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
            $prods = Product::where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
        }
        else{
            $products = ShopifyProduct::orderBy('updated_at', 'DESC')->paginate(20);
            $prods = Product::orderBy('updated_at', 'DESC')->paginate(20);
        }
        $vendors = Vendor::all();


        return view('products.new-index')->with('products',$products)->with('prods',$prods)->with('vendors', $vendors)->with('search', $request->input('search'));
    }

    public function showProductDetails($id) {
        $product = ShopifyProduct::find($id);
        $vendors = Vendor::all();
        $vendor_details = ProductVendorDetail::where('shopify_product_id', $id)->get();

        return view('products.show')->with('product', $product)->with('vendors', $vendors)->with('vendor_details', $vendor_details);
    }

    public function showOrderDetails($id) {
        $order = ShopifyOrder::find($id);


        return view('orders.show')->with('order', $order);
    }

    public function getCustomers() {
        $customers = ShopifyCustomer::paginate(20);
        return view('customers.index')->with('customers', $customers);

    }

    public function getOrders(Request $request) {

        $orders = ShopifyOrder::with(['items.shopify_variant'])->newQuery();

        if ($request->has('search')) {
            $orders->where('name', 'LIKE', '%' . $request->input('search') . '%');
        }
        if($request->has('status')){
            if($request->input('status') == 'unfulfilled'){
                $orders->where('fulfillment_status', null);
            }
            else if($request->input('status') == 'paid'){
                $orders->where('financial_status', 'paid')->get();
            }
            else if($request->input('status') == 'partially_refunded'){
                $orders->where('financial_status', 'partially_refunded');
            }
            else if($request->input('status') == 'authorized'){
                $orders->where('financial_status', 'authorized');
            }
            else if($request->input('status') == 'pending'){
                $orders->where('financial_status', 'pending');
            }
            else if($request->input('status') == 'partially_paid'){
                $orders->where('financial_status', 'partially_paid');
            }
            else if($request->input('status') == 'refunded'){
                $orders->where('financial_status', 'refunded');
            }
            else if($request->input('status') == 'voided'){
                $orders->where('financial_status', 'voided');
            }
            else
            {
                $orders->where('fulfillment_status', $request->input('status'));
            }
        }
        if($request->query('customer')){
            $customer_id = $request->query('customer');
            $orders->where('customer', $customer_id)->get();
        }



      //  $all_orders = ShopifyOrder::all();
        $orders = $orders->orderBy('updated_at', 'ASC')->paginate(30);


        return view('orders.new-index')->with([
            'orders' => $orders,
            'search' => $request->input('search'),
            'status' => $request->input('status')
        ]);
    }

    public function storeOrders($next = null)
    {
        $api = ShopsController::config();
        $orders = $api->rest('GET', '/admin/orders.json', [
            'limit' => 250,
            'page_info' => $next
        ]);


        foreach ($orders['body']['container']['orders'] as $order) {
            $this->createOrder($order);
        }

        if (isset($orders['link']['next'])) {
            echo $orders['link']['next'] . "<br>";
           // $this->storeOrders($orders['link']['next']);
        }
    }

    public function showBulkFulfillments(Request $request)
    {
        $orders_array = explode(',', $request->input('orders'));

        if (count($orders_array) > 0) {
            $orders = ShopifyOrder::whereIn('id', $orders_array)->newQuery();

            $orders->whereHas('line_items', function ($q) {
                $q->where('quantity', '>', 0);
            });
            $orders = $orders->get();

            $total_quantity = 0;
            $fulfillable_quantity = 0;

            return view('orders.bulk-fulfillment')->with([
                'orders' => $orders,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function createOrder($order) {

        if(ShopifyOrder::where('id', $order['id'])->exists()) {
            $o = ShopifyOrder::find($order['id']);
        }
        else {
            $o = new ShopifyOrder();
        }

        if(array_key_exists("shipping_address",$order)) {
            $o->shipping_address = json_encode($order['shipping_address']);
        }

        if(array_key_exists("billing_address",$order)) {
            $o->billing_address = json_encode($order['billing_address']);
        }

        if(array_key_exists("customer",$order)) {
            $o->customer = $order['customer']['id'];
        }
        if(count($order["fulfillments"])>0) {
            $o->fulfillments = json_encode($order['fulfillments']);
        }

        $o->id = $order['id'];
        $o->total_line_item_price = $order['total_line_items_price'];
        $o->total_price = $order['total_price'];
        $o->currency = $order['currency'];
        $o->name = $order['name'];
        $o->fulfillment_status = $order['fulfillment_status'];
        $o->financial_status = $order['financial_status'];
        $o->processing_method = $order['processing_method'];
        $o->processed_at = date('Y-m-d h:i:s',strtotime($order['created_at']));
        $o->line_items = json_encode($order['line_items']);
        $o->shipping_lines = json_encode($order['shipping_lines']);
        $o->notes = $order['note'];
        $o->save();

        $line_item_obj = json_decode($o->line_items);

        $line_items_count = count($line_item_obj);

        if($line_items_count !=0) {
            foreach ($line_item_obj as $item) {

                $line = new LineItem();
                $line->id = $item->id;
                $line->variant_id = $item->variant_id;
                $line->title = $item->title;
                $line->quantity = $item->quantity;
                $line->sku = $item->sku;
                $line->product_id = $item->product_id;
                $line->price = $item->price;
                $line->shopify_order_id = $order['id'];
                $line->save();
            }
        }
    }

    public function storeCustomers($next = null)
    {
        $api = ShopsController::config();

        $customers = $api->rest('GET', '/admin/customers.json', [
            'limit' => 250,
            'page_info' => $next
        ]);

        foreach ($customers['body']['container']['customers'] as $customer) {
            $this->createCustomer($customer);
        }

        if (isset($customers['link']['next'])) {
            echo $customers['link']['next'] . "<br>";
            $this->storeCustomers($customers['link']['next']);
        }

    }

    public function createCustomer($customer) {
        if(ShopifyCustomer::where('id', $customer['id'])->exists()) {
            $c = ShopifyCustomer::find($customer['id']);
        }
        else {
            $c = new ShopifyCustomer();
        }

        $c->id = $customer['id'];
        $c->email = $customer['email'];
        $c->first_name = $customer['first_name'];
        $c->last_name = $customer['last_name'];
        $c->orders_count = $customer['orders_count'];
        $c->state = $customer['state'];
        $c->total_spent = $customer['total_spent'];
        $c->last_order_id = $customer['last_order_id'];
        $c->note = $customer['note'];
        $c->verified_email = $customer['verified_email'];
        $c->phone = $customer['phone'];
        $c->tags = $customer['tags'];
        $c->last_order_name = $customer['last_order_name'];
        $c->currency = $customer['currency'];
        if(array_key_exists("default_address",$customer)) {
            $c->default_address = json_encode($customer['default_address']);
        }
        $c->addresses = json_encode($customer['addresses']);
        $c->save();
    }


    public function storeProducts($next = null)
    {
        $api = ShopsController::config();

        $products = $api->rest('GET', '/admin/products.json', [
            'limit' => 250,
            'page_info' => $next
        ]);

        foreach ($products['body']['container']['products'] as $product) {
            $this->createProduct($product);
        }

        if (isset($products['link']['next'])) {
            $this->storeProducts($products['link']['next']);
        }
    }

    public function createProduct($product) {

        if(ShopifyProduct::where('id', $product['id'])->exists()) {
            $p = ShopifyProduct::find($product['id']);
        }
        else {
            $p = new ShopifyProduct();
        }

        $p->id = $product['id'];
        $p->title =  $product['title'];
        $p->body_html = $product['body_html'];
        $p->vendor =  $product['vendor'];
        $p->product_type =  $product['product_type'];
        $p->handle = $product['handle'];
        $p->published_at = $product['published_at'];
        $p->template_suffix = $product['template_suffix'];
        $p->published_scope = $product['published_scope'];
        $p->tags =json_encode($product['tags']);
        $p->variants = json_encode($product['variants']);
        $p->options = json_encode($product['options']);
        $p->images = json_encode($product['images']);
        $p->image = json_encode($product['image']);
        $p->save();

        if($p->variants){
            $variants = json_decode($p->variants);
            foreach ($variants as $variant) {
                ShopifyVarient::create([
                    'id' => $variant->id,
                    'shopify_product_id'=> $variant->product_id,
                    'title'=> $variant->title,
                    'price'=> $variant->price,
                    'sku'=> $variant->sku,
                    'position'=> $variant->position,
                    'inventory_policy'=> $variant->inventory_policy,
                    'compare_at_price'=> $variant->compare_at_price,
                    'fulfillment_service'=> $variant->fulfillment_service,
                    'inventory_management'=> $variant->inventory_management,
                    'option1'=> $variant->option1,
                    'option2'=> $variant->option2,
                    'option3'=> $variant->option3,
                    'taxable'=> $variant->taxable,
                    'barcode'=> $variant->barcode,
                    'grams'=> $variant->grams,
                    'image_id'=> $variant->image_id,
                    'weight'=> $variant->weight,
                    'weight_unit'=> $variant->weight_unit,
                    'inventory_item_id'=> $variant->inventory_item_id,
                    'inventory_quantity'=> $variant->inventory_quantity,
                    'old_inventory_quantity'=> $variant->old_inventory_quantity,
                    'requires_shipping'=> $variant->requires_shipping,
                ]);
            }
        }

        if($p->images){
            $images = json_decode($p->images);

            foreach ($images as $image) {
                ProductImage::create([
                    'shopify_id' => $image->id,
                    'product_id'=> $image->product_id,
                    'position'=> $image->position,
                    'alt'=> $image->alt,
                    'width'=> $image->width,
                    'height'=> $image->height,
                    'src'=> $image->src,
                    'variant_ids' => json_encode($image->variant_ids),
                ]);
            }
        }
    }

    public function addVendorForProduct(Request $request, $id) {

        $vendor_id_array = [];
        $product_price_array = [];
        $product_link_array = [];
        $notes_array = [];
        $flag = false;
        $create_flag = false;

        $vendor_id_array = array_merge($vendor_id_array, $request->vendor_id);
        $product_price_array = array_merge($product_price_array, $request->product_price);
        $product_link_array = array_merge($product_link_array, $request->product_link);
        $notes_array = array_merge($notes_array, $request->product_notes);

        for($i =0; $i< count($vendor_id_array); $i++) {

            $vendor = ProductVendorDetail::where('shopify_product_id', $id)->where('vendor_id', $vendor_id_array[$i])->first();
            if($vendor == null) {

                if($product_price_array[$i] == null && $product_link_array[$i] == null) {

                }
                else{
                    ProductVendorDetail::create([
                        'shopify_product_id' => $id,
                        'vendor_id' =>  $vendor_id_array[$i],
                        'product_price' => $product_price_array[$i],
                        'product_link' => $product_link_array[$i],
                        'notes' => $notes_array[$i],
                    ]);
                    $create_flag = true;
                }
            }
            else {
//                $vendor->update([
//                    'shopify_product_id' => $id,
//                    'vendor_id' =>  $vendor_id_array[$i],
//                    'product_price' => $product_price_array[$i],
//                    'product_link' => $product_link_array[$i],
//                    'notes' => $notes_array[$i],
//                ]);
//
                $flag = true;
            }
        }

        if($flag && !$create_flag) {
            return redirect()->back()->with('error', 'Nothing new to add!');
        }

        return redirect()->back()->with('success', 'Vendors added successfully!');

    }

    public function getUsers() {
        $users = User::whereHas('roles', function ($query) {
            return $query->where('name','!=', 'admin');
        })->orderBy('updated_at', 'DESC')->paginate(20);

        return view('shops.add-user')->with('users', $users);
    }

    public function storeUser(Request $request) {
        $this->validate($request, [
            'name' =>  'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'type' => 'required'
        ]);

        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password)
        ]);

        $user->assignRole($request->type);


        return redirect()->back()->with('success', 'User created successfully!');


    }

    public function getOutsourceProducts() {
        $products = Product::all();

        return view('products.outsource-products')->with('products', $products);
    }

    public function approveProduct(Request $request, $id) {
        $product = Product::find($id);
        $product->approved = 1;
        $product->notes = $request->notes;
        $product->save();

        return redirect()->back()->with('success', "Product Approved successfully!");

    }

    public function rejectProduct(Request $request, $id) {
        $product = Product::find($id);
        $product->approved = 0;
        $product->notes = $request->notes;
        $product->save();

        return redirect()->back()->with('success', "Product Rejected!");
    }

    public function changeOrderStatus(Request $request, $id) {

        $order = ShopifyOrder::find($id);

        if($request->status == 'Fulfilled') {
            $order->fulfillment_status = 'fulfilled';
            $order->save();

            Log::create([
                'user_id' => Auth::user()->id,
                'attempt_time' => Carbon::now()->toDateTimeString(),
                'attempt_location_ip' => $request->getClientIp(),
                'type' => 'Order Status Changed',
                'shopify_order_id' => $order->id
            ]);

            $api = ShopsController::config();
            $fulfillment_array_to_be_passed = [
                "fulfillment"=> [
                    "tracking_number"=> $request->tracking_number,
                    "tracking_url"=> $request->tracking_url,
                    "tracking_company"=> $request->shipping_carrier,
                    "location_id" => '8749154419'
                ]
              ];

            $response = $api->rest('POST', 'admin/orders/'.$order->id.'/fulfillments.json', $fulfillment_array_to_be_passed, [],true);

            if(!$response['errors']) {
                return redirect()->back()->with('success', 'Order status changed successfully!');
            }
            else {
                return redirect()->back()->with('error', 'Request cannot be proceed');
            }

            return redirect()->back()->with('success', 'Order status changed successfully!');


        }

        if($request->status == 'Unfulfilled') {
            $order->fulfillment_status = null;
            $order->save();
            return redirect()->back()->with('success', 'Order status Updated successfully!');
        }

        if($request->status == 'Paid') {
            $order->financial_status = 'paid';
            $order->save();
            return redirect()->back()->with('success', 'Order status Updated successfully!');
        }

        if($request->status == 'Unpaid') {
            $order->financial_status = 'pending';
            $order->save();
            return redirect()->back()->with('success', 'Order status Updated successfully!');
        }

    }

    public function storeOrderNotes(Request $request, $id) {
        $order = ShopifyOrder::find($id);

        $order->notes = $request->notes;
        $order->save();

        return redirect()->back()->with('success', "Notes Added Successfully!");
    }

    public function deleteUser($id) {
        $user = User::find($id);

        $user->removeRole($user->roles->first());

        $user->delete();

        return redirect()->back()->with('success', 'User removed successfully!');
    }

    public function editUser(Request $request, $id) {
        $user = User::find($id);

        if($request->password) {
           $this->validate($request, [
               'password' => 'required|string|min:8|confirmed',
               'password_confirmation' => 'required|string|min:8'
           ]);
           $user->password = Hash::make($request->password);
        }

        $this->validate($request, [
            'name' =>  'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'type' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->removeRole($user->roles->first());
        $user->assignRole($request->type);


        return redirect()->back()->with('success', 'User Updated successfully!');

    }

    public function showUser($id) {
        $user = User::find($id);
        $products = Product::where('outsource_id', $id)->paginate(10);
        $last_product = Product::where('outsource_id', $id)->orderBy('updated_at', 'DESC')->first();
        $logs = Log::where('user_id', $id)->paginate(20);

        return view('shops.show-user')->with('user', $user)->with('products', $products)->with('product', $last_product)->with('logs', $logs);
    }

    public function storeOrderVendor(Request $request) {


        if(isset($request->vendors)) {
            foreach ($request->line as $line) {
                $vendor_array = array();

                foreach ($request->vendors as $vendor) {
                    $order_vendor = new OrderVendor();
                    $vendorDetail = ProductVendorDetail::find($vendor);


                    $order_vendor->vendor_id = $vendorDetail->vendor_id;
                    $order_vendor->vendor_product_id = $vendorDetail->shopify_product_id;
                    $order_vendor->line_id = $line;
                    $order_vendor->save();

                    $vendor = Vendor::find($vendorDetail->vendor_id);
                    array_push($vendor_array, $vendor->name);

                }

                $line_item = LineItem::find($line);
                $line_item->vendor = $vendor_array;
                $line_item->save();
            }

            return redirect()->back()->with('success', 'Vendors added');
        }

        return redirect()->back()->with('error', 'Select vendors to add');
    }

    public function getReports(Request $request) {

        if($request->query('datefilter')) {
            $query = $request->query('datefilter');
            $dates_array = explode('- ', $query);

            $start_date = date('Y-m-d h:i:s',strtotime($dates_array[0]));
            $end_date = date('Y-m-d h:i:s',strtotime($dates_array[1]));

            $orders_total_price = ShopifyOrder::whereBetween('processed_at', [$start_date, $end_date])->sum('total_price');

            $ordersQ = DB::table('shopify_orders')
                ->select(DB::raw('DATE(processed_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                ->whereBetween('processed_at', [$start_date, $end_date])
                ->groupBy('date')
                ->get();

        }
        else{
            $orders_total_price = ShopifyOrder::sum('total_price');

            $ordersQ = DB::table('shopify_orders')
                ->select(DB::raw('DATE(processed_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                ->groupBy('date')
                ->get();

        }

        // Graph calculations
        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

        // Top products
        $top_products_stores = ShopifyProduct::join('line_items', function ($join) {
                    $join->on('line_items.product_id', '=', 'shopify_products.id')
                        ->join('shopify_orders', function ($o) {
                            $o->on('line_items.shopify_order_id', '=', 'shopify_orders.id')
                                ->where('shopify_orders.financial_status', '=', 'paid');
                        });
        })->select('shopify_products.*', DB::raw('sum(line_items.quantity) as sold'), DB::raw('sum(line_items.price) as selling_cost'))
            ->groupBy('shopify_products.id' )
            ->orderBy('sold', 'DESC')
            ->get()
            ->take(5);



        // Vendor cost calculation
        $orders = ShopifyOrder::all();
        $price = 0;
        foreach ($orders as $order) {

            foreach ($order->items as $item) {
                $vendor = $item->vendor;
                if($vendor != null) {
                    $vendors = json_decode($vendor);
                    foreach ($vendors as $vendor) {
                        $ven = Vendor::where('name', $vendor)->first();
                        $vendor_details = ProductVendorDetail::where('vendor_id', $ven->id)->first();
                        $price += $vendor_details->product_price;
                    }
                }
                else {
                    $price +=0;
                }
            }

        }
        $cost =  number_format($price, 2);


        return view('products.reports')->with([
            'orders_sum' => $orders_total_price,
            'graph_one_labels' => $graph_one_order_dates,
            'graph_one_values' => $graph_one_order_values,
            'graph_two_values' => $graph_two_order_values,
            'top_products_stores' => $top_products_stores,
            'cost' => $cost
        ]);

    }

    public static function setDate($d) {
        $str = $d;
        $date = strtotime($str);
        return date('d/M/Y ', $date);
    }

    public function getLogs(Request $request) {

        $logs = Log::orderBy('updated_at', 'DESC')->paginate(30);

        return view('shops.log')->with('logs', $logs);
    }


    public function createWebhooks(Request $request) {
        $api = ShopsController::config();
        $product_array = [
            "webhook"=> [
                "topic"=> "customers/create",
                "address"=> "https://phpstack-176572-1491780.cloudwaysapps.com/webhook/product/create",
                "format"=> "json",
            ]
        ];
        $order_array = [
            "webhook"=> [
                "topic"=> "customers/update",
                "address"=> "https://phpstack-176572-1491780.cloudwaysapps.com/webhook/order/create",
                "format"=> "json",
            ]
        ];
        $api->rest('POST', '/admin/webhooks.json', $product_array, [], true);
        $api->rest('POST', '/admin/webhooks.json', $order_array, [], true);
    }

    public function getWebhooks() {
        $api = ShopsController::config();
        $response = $api->rest('GET', '/admin/webhooks.json', null, [], true);
        dd($response);
    }

    public function productCreateWebhook(Request $request){
//        $input = file_get_contents('php://input');
//        $product = json_decode($input, true);
//        $this->createProduct($product);
            $webhook = new Webhook();
            $webhook->content = 'aa';
            $webhook->save();
    }

    public function orderCreateWebhook(Request $request){
        $input = file_get_contents('php://input');
        $order = json_decode($input, true);
        $this->createOrder($order);
    }



    public function addTracking(Request $request) {

        if(Tracking::count() > 0) {
            Tracking::truncate();
        }
        $tracking = new Tracking();
        $tracking->tracking_number = $request->tracking_number;
        $tracking->tracking_url = $request->tracking_url;
        $tracking->tracking_company = $request->shipping_carrier;
        $tracking->location_id = '6122438719';
        $tracking->save();

        return response()->json(['data'=> 'success', 'tracking'=> $tracking]);
    }


    public function fulfillOrders(Request $request) {


        if(isset($request->tracking_number)) {
            $fulfillment_array_to_be_passed = [
                "fulfillment"=> [
                    "tracking_number"=> $request->tracking_number,
                    "tracking_url"=> $request->tracking_url,
                    "tracking_company"=> $request->tracking_company,
                    "location_id" => '8749154419'
                ]
            ];
        }
        else {
            $fulfillment_array_to_be_passed = [
                "fulfillment"=> [
                    "tracking_number"=> null,
                    "location_id" => '8749154419'
                ]
            ];
        }
        $orders = $request->orders;

        foreach ($orders as $id) {
            $order = ShopifyOrder::find($id);
            $order->fulfillment_status = 'fulfilled';
            $order->save();

            Log::create([
                'user_id' => Auth::user()->id,
                'attempt_time' => Carbon::now()->toDateTimeString(),
                'attempt_location_ip' => $request->getClientIp(),
                'type' => 'Order Status Changed',
                'shopify_order_id' => $order->id
            ]);

            $api = ShopsController::config();
            $response = $api->rest('POST', 'admin/orders/'.$id.'/fulfillments.json', $fulfillment_array_to_be_passed, [],true);

            if($response['errors']) {
                dd($response);
                return redirect(route('admin.orders'))->with('error', 'Request cannot be proceed for order #'.$order->name);
            }
        }

        return redirect(route('admin.orders'))->with('success', 'Orders Fulfilled Successfully!');
    }





}
