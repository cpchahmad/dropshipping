<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\Expense;
use App\LineItem;
use App\Log;
use App\LoginDetails;
use App\OrderFulfillment;
use App\OrderTracking;
use App\OrderVendor;
use App\Product;
use App\ProductImage;
use App\ProductVendorDetail;
use App\ShippingPrice;
use App\Shop;
use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyOrderNote;
use App\ShopifyProduct;
use App\ShopifyVarient;
use App\Tracking;
use App\User;
use App\Vendor;
use App\Webhook;
use App\WordpressLineItem;
use App\WordpressOrder;
use App\WordpressProduct;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\False_;
use SebastianBergmann\Diff\Line;

class AdminController extends Controller
{

    public function createShopSection(Request $request){

        $request->session()->put('current_shop_domain', $request->shop_domain);

        return response()->json();
    }

    public function get_products(Request $request) {

        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){

            if ($request->has('search')) {
                $products = WordpressProduct::where('name', 'LIKE', '%' . $request->input('search') . '%')->orWhereHas('wordpress_product_variations', function($q) use ($request) {
                    $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
                })->paginate(20);

                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
            }
            else{
                $products = WordpressProduct::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
            }

//            $products = WordpressProduct::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
            $vendors = Vendor::all();

            return view('products.wordpress_products_index')->with('products',$products)->with('prods',$prods)->with('vendors', $vendors)->with('search', $request->input('search'));
        }
        elseif($shop_type['shop_type'] == "shopify"){

            if ($request->has('search')) {
                $products = ShopifyProduct::where('title', 'LIKE', '%' . $request->input('search') . '%')->orWhereHas('shopify_varients', function($q) use ($request) {
                    $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
                })->paginate(20);

                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
            }
            else{
                $products = ShopifyProduct::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
            }
            $vendors = Vendor::all();

            return view('products.new-index')->with('products',$products)->with('prods',$prods)->with('vendors', $vendors)->with('search', $request->input('search'));
        }

    }

    public function allProducts(Request $request) {
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "shopify") {
            if ($request->has('search')) {
                $products = ShopifyProduct::where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
            } else {
                $products = ShopifyProduct::orderBy('updated_at', 'DESC')->paginate(20);
            }
            $vendors = Vendor::all();

            return view('products.all')->with('products', $products)->with('vendors', $vendors)->with('search', $request->input('search'));
        }elseif($shop_type['shop_type'] == "wordpress"){

            if ($request->has('search')) {
                $products = WordpressProduct::where('name', 'LIKE', '%' . $request->input('search') . '%')->orWhereHas('wordpress_product_variations', function($q) use ($request) {
                    $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
                })->paginate(20);

                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->where('title', 'LIKE', '%' . $request->input('search') . '%')->paginate(20);
            }
            else{
                $products = WordpressProduct::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
                $prods = Product::where('shop_id', session()->get('current_shop_domain'))->orderBy('updated_at', 'DESC')->paginate(20);
            }
            $vendors = Vendor::all();

            return view('products.all_wordpress_product')->with('products',$products)->with('prods',$prods)->with('vendors', $vendors)->with('search', $request->input('search'));

        }
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

        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();
//        Wordpress Orders
        if($shop_type['shop_type'] == "wordpress"){

            $orders = WordpressOrder::query();
            if ($request->has('search')) {
                $orders = $orders->where('shop_id', session()->get('current_shop_domain'))->where('number', 'LIKE', '%' . $request->input('search') . '%')
                    ->orderBy('date_paid', 'DESC')->paginate(30);
            }else{
                $orders =WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->orderBy('date_paid', 'DESC')->paginate(30);
            }

            return view('orders.wordpress_orders_index')->with([
                'orders' => $orders,
                'search' => $request->input('search'),
                'status' => $request->input('status')
            ]);


//            Shopify Orders
        }elseif($shop_type['shop_type'] == "shopify"){
            $orders = ShopifyOrder::with(['items.shopify_variant.shopify_product.product_vendor_details'])->newQuery();

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

            $orders = $orders->where('shop_id', session()->get('current_shop_domain'))->orderBy('processed_at', 'DESC')->paginate(30);

            return view('orders.new-index')->with([
                'orders' => $orders,
                'search' => $request->input('search'),
                'status' => $request->input('status')
            ]);
        }

    }

    public function adminSyncOrders($id) {
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();
//        Wordpress Orders
        if($shop_type['shop_type'] == "shopify") {
            $api = ShopsController::config($id);
            $orders = $api->rest('GET', '/admin/orders.json', [
                'limit' => 100,
            ]);

            if (!$orders['errors']) {
                foreach ($orders['body']['container']['orders'] as $order) {
                    $this->createOrder($order, session()->get('current_shop_domain'));
                }

                return redirect()->back()->with('success', 'Orders Synced Successfully!');
            }
            return redirect()->back()->with('error', 'Your Request cannot be procceed, Please try again!');
        }elseif($shop_type['shop_type'] == "wordpress"){
            $wordpress = new WordpressController();
            $wordpress->sync_wordpress_order($id);
            return redirect()->back()->with('success', 'Order Sync Successfully !');
        }


    }

    public function storeOrders($id, $next = null)
    {
        $api = ShopsController::config($id);

        $orders = $api->rest('GET', '/admin/orders.json', [
            'limit' => 250,
            'page_info' => $next
        ]);


        foreach ($orders['body']['container']['orders'] as $order) {
            $this->createOrder($order, $id);
        }

        if (isset($orders['link']['next'])) {
            echo $orders['link']['next'] . "<br>";
            $this->storeOrders($orders['link']['next']);
        }
    }

    public function showBulkFulfillments(Request $request)
    {
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "shopify") {
            $orders_array = explode(',', $request->input('orders'));

            if (count($orders_array) > 0) {
                $orders =ShopifyOrder::whereIn('id', $orders_array)->newQuery();


                $orders->whereHas('items', function ($q) {
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

        }elseif($shop_type['shop_type'] == "wordpress"){
            $orders_array = explode(',', $request->input('orders'));

            if (count($orders_array) > 0) {
//                dd($orders_array);
                $orders =WordpressOrder::whereIn('wordpress_order_id', $orders_array)->newQuery();

                $orders->whereHas('items', function ($q) {
                    $q->where('quantity', '>', 0);
                });
                $orders = $orders->get();
//                dd($orders);
                $total_quantity = 0;
                $fulfillable_quantity = 0;

                return view('orders.wordpress-bulk-fulfillment')->with([
                    'orders' => $orders,
                ]);
            } else {
                return redirect()->back();
            }
        }

    }

    public function createOrder($order, $id) {

        if(ShopifyOrder::where('id', $order['id'])->where('shop_id', $id)->exists()) {
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
        $o->shop_id = $id;
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


        foreach ($order['line_items'] as $item) {
            if(LineItem::where('id', $item['id'])->where('shop_id', $id)->exists()) {
                $line = LineItem::find($item['id']);
            }
            else {
                $line = new LineItem();
            }

            $line->id = $item['id'];
            $line->shop_id = $id;
            $line->variant_id = $item['variant_id'];
            $line->title = $item['title'];
            $line->quantity = $item['quantity'];
            $line->sku = $item['sku'];
            $line->product_id = $item['product_id'];
            $line->price = $item['price'];
            $line->shopify_order_id = $order['id'];
            $line->fulfillable_quantity = $item['fulfillable_quantity'];
            $line->properties = json_encode($item['properties']);

            $line->save();
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


    public function storeProducts($id,$next = null)
    {
//        dd($id);
        $api = ShopsController::config($id);

        $products = $api->rest('GET', '/admin/products.json', [
            'limit' => 250,
            'page_info' => $next
        ]);

        foreach ($products['body']['container']['products'] as $product) {
            $this->createProduct($product, $id);
        }

        if (isset($products['link']['next'])) {
            $this->storeProducts($products['link']['next']);
        }
    }

    public function createProduct($product, $id) {

        if(ShopifyProduct::where('id', $product['id'])->where('shop_id', $id)->exists()) {
            $p = ShopifyProduct::find($product['id']);
        }
        else {
            $p = new ShopifyProduct();
        }

        $p->id = $product['id'];
        $p->shop_id = $id;
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
                if(ShopifyVarient::where('id', $variant->id)->where('shop_id', $id)->exists()) {
                    $var = ShopifyVarient::find($variant->id);
                }
                else {
                    $var = new ShopifyVarient();
                }

                $var->id = $variant->id;
                $var->shop_id = $id;
                $var->shopify_product_id= $variant->product_id;
                $var->title= $variant->title;
                $var->price= $variant->price;
                $var->sku= $variant->sku;
                $var->position= $variant->position;
                $var->inventory_policy= $variant->inventory_policy;
                $var->compare_at_price= $variant->compare_at_price;
                $var->fulfillment_service= $variant->fulfillment_service;
                $var->inventory_management= $variant->inventory_management;
                $var->option1= $variant->option1;
                $var->option2= $variant->option2;
                $var->option3= $variant->option3;
                $var->taxable= $variant->taxable;
                $var->barcode= $variant->barcode;
                $var->grams= $variant->grams;
                $var->image_id= $variant->image_id;
                $var->weight= $variant->weight;
                $var->weight_unit= $variant->weight_unit;
                $var->inventory_item_id= $variant->inventory_item_id;
                $var->inventory_quantity= $variant->inventory_quantity;
                $var->old_inventory_quantity= $variant->old_inventory_quantity;
                $var->requires_shipping= $variant->requires_shipping;
                $var->save();

            }
        }

        if($p->images){
            $images = json_decode($p->images);

            foreach ($images as $image) {
                $product_image = ProductImage::where('shopify_id', $image->id)->where('shop_id', $id)->first();
                if($product_image === null){
                    $product_image = new ProductImage();
                }
                $product_image->shop_id = $id;
                $product_image->shopify_id = $image->id;
                $product_image->product_id = $image->product_id;
                $product_image->position = $image->position;
                $product_image->alt = $image->alt;
                $product_image->width = $image->width;
                $product_image->height = $image->height;
                $product_image->src = $image->src;
                $product_image->variant_ids = json_encode($image->variant_ids);
                $product_image->save();

            }
        }
    }

    public function addVendorForProduct(Request $request, $id) {

        $product_price_array = [];
        $product_link_array = [];
        $vendor_name_array = [];
        $moq_array = [];
        $lead_time_array = [];
        $weight_array = [];
        $length_array = [];
        $width_array = [];
        $height_array = [];
        $volume_array = [];


        $product_price_array = array_merge($product_price_array, $request->product_price);
        $product_link_array = array_merge($product_link_array, $request->product_link);
        $vendor_name_array = array_merge($vendor_name_array, $request->vendor_name);
        $moq_array = array_merge($moq_array, $request->moq);
        $lead_time_array = array_merge($lead_time_array, $request->leads_time);
        $weight_array = array_merge($weight_array, $request->weight);
        $length_array = array_merge($length_array, $request->length);
        $width_array = array_merge($width_array, $request->width);
        $height_array = array_merge($height_array, $request->height);

        $shop_id = intval(session()->get('current_shop_domain'));
        for($i =0; $i< count($vendor_name_array); $i++) {

            if(!(is_null($vendor_name_array[$i]))) {
                DB::table('product_vendor_details')->insert([
                    'shop_id' => $shop_id,
                    'shopify_product_id' => $id,
                    'name' =>  $vendor_name_array[$i],
                    'cost' => $product_price_array[$i],
                    'url' => $product_link_array[$i],
                    'moq' => $moq_array[$i],
                    'leads_time' => $lead_time_array[$i],
                    'weight' => $weight_array[$i],
                    'length' => $length_array[$i],
                    'width' => $width_array[$i],
                    'height' => $height_array[$i],
                    'volume' => ($length_array[$i] * $width_array[$i] * $height_array[$i])
                ]);
            }

        }


        return redirect()->back()->with('success', 'Vendors added successfully!');

    }

    public function deleteVendorForProduct($id) {

        if(OrderVendor::where('shop_id', session()->get('current_shop_domain'))->where('vendor_id', $id)->exists()) {
            return response()->json(['data'=> 'error']);
        }
//        dd($id);
        $vendor = ProductVendorDetail::where('id', $id)->where('shop_id', session()->get('current_shop_domain'))->first();
        $vendor->delete();

        return response()->json(['data'=> 'success']);
    }

    public function editVendorForProduct(Request $request, $id) {
        $vendor = ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();
        $vendor->name = $request->name;
        $vendor->cost = $request->price;
        $vendor->url = $request->link;
        $vendor->moq = $request->moqs;
        $vendor->leads_time = $request->lead_time;
        $vendor->weight = $request->weight;
        $vendor->length = $request->length;
        $vendor->width = $request->width;
        $vendor->height = $request->height;
        $vendor->volume = ($request->length * $request->width * $request->height);
        $vendor->save();

        return response()->json(['data'=> 'success', 'vendor' => $vendor]);
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


        $user = new User();
        $user->shop_id = session()->get('current_shop_domain');
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        foreach ($request->type as $type) {
            $user->assignRole($type);
        }

        return redirect()->back()->with('success', 'User created successfully!');


    }

    public function getOutsourceProducts() {
        $products = Product::all();

        return view('products.outsource-products')->with('products', $products);
    }

    public function approveProduct(Request $request, $id) {
        $product = Product::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();
        $product->approved = 1;
        $product->notes = $request->notes;
        $product->save();

        return redirect()->back()->with('success', "Product Approved successfully!");

    }

    public function rejectProduct(Request $request, $id) {
        $product = Product::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();
        $product->approved = 2;
        $product->notes = $request->notes;
        $product->save();

        return redirect()->back()->with('success', "Product Rejected!");
    }

    public function changeOrderStatus(Request $request, $id ) {
//        dd($request->all());
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){

            $current_shop_domain = Shop::where('id', session()->get('current_shop_domain'))->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();
            $fulfillable_quantities = $request->input('item_fulfill_quantity');
//            dd($request->input('item_fulfill_quantity'));
            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);
            $data = [
                'status' => 'completed',
            ];


//            $wordpress_order = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('wordpress_order_id', $id)->first();
//            foreach (json_decode($wordpress_order->line_items) as $item){
//
//                foreach ($request->input('item_id') as $index => $items) {
////                $line_item = LineItem::find($item);
////                dd(json_decode($wordpress_order->line_items));
//
//                    if ($item != null && $fulfillable_quantities[$index] > 0) {
//                        array_push($data['line_items'], [
//                            "product_id" => $item->product_id,
//                            "variation_id" => $item->variation_id,
//                            "quantity" => $fulfillable_quantities[$index],
//                        ]);
//                    }
//                }
//            }

            $fulffiled = $woocommerce->put('orders/'.$id, $data);

            if($fulffiled->status == 'completed'){
//                dd($request->all());
                $order = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();

                $order_fulfillment = new OrderFulfillment();

                if(!(is_null($request->shipping_price))) {

                    if($request->shipping_currency == 'usd') {
                        $order_fulfillment->shipping_price_usd = $request->shipping_price;
                        $order_fulfillment->shipping_currency = $request->shipping_currency;
                    }
                    else{
                        $order_fulfillment->shipping_price_usd = $request->shipping_price / 6.6;
                        $order_fulfillment->shipping_price_rmb = ((double) $request->shipping_price);
                        $order_fulfillment->shipping_currency = $request->shipping_currency;
                    }

                    DB::table('logs')->insert([
                        'shop_id' => session()->get('current_shop_domain'),
                        'user_id' => Auth::user()->id,
                        'user_role' => Auth::user()->role,
                        'attempt_time' => Carbon::now()->toDateTimeString(),
                        'attempt_location_ip' => $request->getClientIp(),
                        'type' => 'Order Shipping Price added',
                        'shopify_order_id' => $id
                    ]);

                }

                foreach ($request->input('item_id') as $line) {

                    if ($request->input('item_vendor_' . $line)) {

                        $vendor_array = array();

                        $vendorDetail = ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('id', $request->input('item_vendor_' . $line))->first();

                        if (OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->exists()) {
                            OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->delete();
                        }

                        $order_vendor = new OrderVendor();
                        $order_vendor->shop_id = session()->get('current_shop_domain');
                        $order_vendor->vendor_id = $vendorDetail->id;
                        $order_vendor->vendor_product_id = $vendorDetail->shopify_product_id;
                        $order_vendor->product_price = $request->input('product_price_' . $line);
                        $order_vendor->line_id = $line;
                        $order_vendor->save();

                        array_push($vendor_array, $vendorDetail->id);
//                        $wordpress_order = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('wordpress_order_id', $id)->first();
//                        foreach (json_decode($wordpress_order->line_items) as $item) {
//                            $item->vendor = $vendor_array;
//                            $item->save();
//                        }

                        $line_item = WordpressLineItem::where('shop_id', session()->get('current_shop_domain'))->where('id', $line)->first();
                        $line_item->vendor = $vendor_array;
                        $line_item->save();

                        DB::table('logs')->insert([
                            'shop_id' => session()->get('current_shop_domain'),
                            'user_id' => Auth::user()->id,
                            'user_role' => Auth::user()->role,
                            'attempt_time' => Carbon::now()->toDateTimeString(),
                            'attempt_location_ip' => $request->getClientIp(),
                            'type' => 'Vendor added to order',
                            'shopify_order_id' => $id
                        ]);
                    }
                }

                if(is_null($request->tracking_number) && is_null($request->tracking_url)) {
                    $order_fulfillment->shop_id = session()->get('current_shop_domain');
                    $order_fulfillment->shopify_order_id = $id;
                    $order_fulfillment->save();
                }
                //6122438719
                else {
                    $order_fulfillment->shopify_order_id = $id;
                    $order_fulfillment->shop_id = session()->get('current_shop_domain');
                    $order_fulfillment->tracking_number = $request->tracking_number;
                    $order_fulfillment->tracking_url = $request->tracking_url;
                    $order_fulfillment->tracking_company = $request->shipping_carrier;

                    $order_fulfillment->save();
                }

                $order_fulfilled_update = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('wordpress_order_id', $id)->first();
                $order_fulfilled_update->status = 'completed';
                $order_fulfilled_update->update();

                return redirect()->back()->with('success', 'Order Fulfilled Successfully!');
            }elseif($fulffiled->status != 'completed'){
                return redirect()->back()->with('error', 'Order not Fulfilled!');
            }

        }elseif($shop_type['shop_type'] == "shopify"){

            $shop_id = session()->get('current_shop_domain');

            foreach ($request->input('item_id') as $line) {

                if($request->input('item_vendor_'.$line)) {

                    $vendor_array = array();

                    $vendorDetail = ProductVendorDetail::find($request->input('item_vendor_'.$line));

                    if(OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->exists()) {
                        OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->delete();
                    }

                    $order_vendor = new OrderVendor();
                    $order_vendor->shop_id = session()->get('current_shop_domain');
                    $order_vendor->vendor_id = $vendorDetail->id;
                    $order_vendor->vendor_product_id = $vendorDetail->shopify_product_id;
                    $order_vendor->product_price = $request->input('product_price_'.$line);
                    $order_vendor->line_id = $line;
                    $order_vendor->save();

                    array_push($vendor_array, $vendorDetail->id);

                    $line_item = LineItem::find($line);
                    $line_item->vendor = $vendor_array;
                    $line_item->save();

                    DB::table('logs')->insert([
                        'shop_id' => session()->get('current_shop_domain'),
                        'user_id' => Auth::user()->id,
                        'user_role' => Auth::user()->role,
                        'attempt_time' => Carbon::now()->toDateTimeString(),
                        'attempt_location_ip' => $request->getClientIp(),
                        'type' => 'Vendor added to order',
                        'shopify_order_id' => $line_item->shopify_order_id
                    ]);
                }

            }

            $order = ShopifyOrder::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();
            $order_fulfillment = new OrderFulfillment();
            $fulfillable_quantities = $request->input('item_fulfill_quantity');
//dd($fulfillable_quantities);
            if(!(is_null($request->shipping_price))) {
                if($request->shipping_currency == 'usd') {
                    $order_fulfillment->shop_id = session()->get('current_shop_domain');
                    $order_fulfillment->shipping_price_usd = $request->shipping_price;
                    $order_fulfillment->shipping_currency = $request->shipping_currency;
                    $order_fulfillment->shopify_order_id = $id;
                }
                else{
                    $order_fulfillment->shop_id = session()->get('current_shop_domain');
                    $order_fulfillment->shipping_price_usd = $request->shipping_price / 6.6;
                    $order_fulfillment->shipping_price_rmb = ((double) $request->shipping_price);
                    $order_fulfillment->shipping_currency = $request->shipping_currency;
                    $order_fulfillment->shopify_order_id = $id;
                }

                DB::table('logs')->insert([
                    'shop_id' => session()->get('current_shop_domain'),
                    'user_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role,
                    'attempt_time' => Carbon::now()->toDateTimeString(),
                    'attempt_location_ip' => $request->getClientIp(),
                    'type' => 'Order Shipping Price added',
                    'shopify_order_id' => $order->id
                ]);

            }

            if(is_null($request->tracking_number) && is_null($request->tracking_url)) {
                $data = [
                    "fulfillment" => [
                        "location_id" => "8749154419",
                        "tracking_number"=> null,
                        "line_items" => [

                        ]
                    ]
                ];
            }
            //6122438719
            else {
                $data = [
                    "fulfillment" => [
                        "location_id" => "8749154419",
                        "tracking_number"=> $request->tracking_number,
                        "tracking_url"=> $request->tracking_url,
                        "tracking_company"=> $request->shipping_carrier,
                        "line_items" => [

                        ]
                    ]
                ];

                $order_fulfillment->shop_id = session()->get('current_shop_domain');
                $order_fulfillment->tracking_number = $request->tracking_number;
                $order_fulfillment->tracking_url = $request->tracking_url;
                $order_fulfillment->tracking_company = $request->shipping_carrier;

            }


//dd($request->fulfillable_quantities);
            foreach ($request->input('item_id') as $index => $item) {
                $line_item = LineItem::find($item);
                if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                    array_push($data['fulfillment']['line_items'], [
                        "id" => $line_item->id,
                        "quantity" => $fulfillable_quantities[$index],
                    ]);
                }
            }

            $api = ShopsController::config($shop_id);
//        dd($data);

            $response = $api->rest('POST', 'admin/orders/'.$order->id.'/fulfillments.json', $data);
//        dd($response);
            if(!$response['errors']) {
                return $this->set_fulfilments($request, $id, $fulfillable_quantities, $order, $response, $order_fulfillment);
                return redirect()->back()->with('success', 'Order Mark as fulfilled successfully!');
            }
            else {
                return redirect()->back()->with('error', 'Request cannot be proceed');
            }
        }
    }

    public function set_fulfilments(Request $request, $id, $fulfillable_quantities, $order, $response, $order_fulfillment): \Illuminate\Http\RedirectResponse
    {
        $flag = 0;
        foreach ($request->input('item_id') as $index => $item) {
            $line_item = LineItem::where('shop_id', session()->get('current_shop_domain'))->where('id', $item)->first();
            if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                if ($fulfillable_quantities[$index] == $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'fulfilled';

                } else if ($fulfillable_quantities[$index] < $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'partially-fulfilled';

                }
                $line_item->fulfillable_quantity = $line_item->fulfillable_quantity - $fulfillable_quantities[$index];
                $line_item->fulfillment_response = $response['body']['fulfillment']['name'];
            }
            $line_item->save();
        }

        $quanity = $order->items->sum('quantity');
        $fulfillable_quanity = $order->items->sum('fulfillable_quantity');
        if($fulfillable_quanity == 0){
            $order->fulfillment_status = 'fulfilled';
        }
        else if($fulfillable_quanity == $quanity || $fulfillable_quanity < $quanity){
            $order->fulfillment_status = 'partial';
        }
        $order_fulfillment->fulfillment_response = $response['body']['fulfillment']['name'];
        $order_fulfillment->save();
        $order->save();

        DB::table('logs')->insert([
            'shop_id' => session()->get('current_shop_domain'),
            'user_id' => Auth::user()->id,
            'user_role' => Auth::user()->role,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Order Fulfilled',
            'shopify_order_id' => $order->id
        ]);

        return redirect()->back()->with('success', 'Order Mark as fulfilled successfully!');



//        foreach ($request->input('item_id') as $index => $item) {
//            if ($fulfillable_quantities[$index] > 0) {
//                $fulfillment_line_item = new FulfillmentLineItem();
//                $fulfillment_line_item->fulfilled_quantity = $fulfillable_quantities[$index];
//                $fulfillment_line_item->order_fulfillment_id = $fulfillment->id;
//                $fulfillment_line_item->order_line_item_id = $item;
//                $fulfillment_line_item->save();
//
//            }
//        }
//        if($order->admin_shopify_id != null) {
//            $this->admin_maintainer->admin_order_fullfillment($order, $request, $fulfillment);
//        }
//        $this->notify->generate('Order','Order Fulfillment',$order->name.' line items fulfilled',$order);
//
//        $manager = User::find(Auth::id());
//        $ml = new ManagerLog();
//        $ml->message = 'Order '.$order->name.' line-items fulfillment by Manager processed successfully on ' . now()->format('d M, Y h:i a');
//        $ml->status = "Order Fulfillment";
//        $ml->manager_id = $manager->id;
//        $ml->save();
//
//        return redirect()->route('sales_managers.order.view', $id)->with('success', 'Order Line Items Marked as Fulfilled Successfully!');
    }


    public function storeOrderNotes(Request $request, $id) {

        $notes = new ShopifyOrderNote();

        $notes->shop_id = session()->get('current_shop_domain');
        $notes->notes = $request->notes;
        $notes->shopify_order_id = $request->id;
        $notes->save();

        DB::table('logs')->insert([
            'shop_id' =>  session()->get('current_shop_domain'),
            'user_id' => Auth::user()->id,
            'user_role' => Auth::user()->role,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Order Notes Added',
            'shopify_order_id' => $notes->shopify_order_id
        ]);

        return response()->json(['data'=> 'success', 'note'=> $notes]);
    }

    public function deleteUser($id) {
        $user = User::find($id);

        foreach ($user->roles as $role) {
            $user->removeRole($role);
        }

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

        foreach ($user->roles as $role) {
            $user->removeRole($role);
        }
        foreach ($request->type as $type) {
            $user->assignRole($type);
        }


        return redirect()->back()->with('success', 'User Updated successfully!');

    }

    public function showUser($id) {
        $user = User::where('id', $id)->first();
        $products = Product::where('outsource_id', $id)->paginate(10);
        $last_product = Product::where('outsource_id', $id)->orderBy('updated_at', 'DESC')->first();
        $logs = Log::where('user_id', $id)->orderBy('updated_at', 'DESC')->paginate(20);

        return view('shops.show-user')->with('user', $user)->with('products', $products)->with('product', $last_product)->with('logs', $logs);
    }

    public function storeOrderVendor(Request $request) {

        if(isset($request->vendors)) {
            foreach ($request->line as $line) {
                $vendor_array = array();

                foreach ($request->vendors as $vendor) {
                    $vendorDetail = ProductVendorDetail::find($vendor);

                    if(OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->exists()) {
                        OrderVendor::where(['line_id' => $line, 'vendor_product_id' => $vendorDetail->shopify_product_id])->delete();
                    }

                    $order_vendor = new OrderVendor();
                    $order_vendor->vendor_id = $vendorDetail->id;
                    $order_vendor->vendor_product_id = $vendorDetail->shopify_product_id;
                    $order_vendor->line_id = $line;
                    $order_vendor->save();

                    array_push($vendor_array, $vendorDetail->id);

                }

                $line_item = LineItem::find($line);
                $line_item->vendor = $vendor_array;
                $line_item->save();

                Log::create([
                    'user_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role,
                    'attempt_time' => Carbon::now()->toDateTimeString(),
                    'attempt_location_ip' => $request->getClientIp(),
                    'type' => 'Vendor added to order',
                    'shopify_order_id' => $line_item->shopify_order_id
                ]);
            }

            return redirect()->back()->with('success', 'Vendors added');
        }

        return redirect()->back()->with('error', 'Select vendors to add');
    }

    public function getReports(Request $request) {

        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){
            if($request->query('datefilter')) {
                $query = $request->query('datefilter');
                $dates_array = explode('- ', $query);

                $start_date = date('Y-m-d h:i:s',strtotime($dates_array[0]));
                $end_date = date('Y-m-d h:i:s',strtotime($dates_array[1]));

                $orders_total_price = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->whereBetween('date_paid', [$start_date, $end_date])->sum('total');

                $ordersQ = DB::table('wordpress_orders')
                    ->select(DB::raw('DATE(date_paid) as date'), DB::raw('count(*) as total, sum(total) as total_sum'))
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->groupBy('date')
                    ->get();

            }
            else {
                $orders_total_price = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->sum('total');

                $ordersQ = DB::table('wordpress_orders')
                    ->select(DB::raw('DATE(date_paid) as date'), DB::raw('count(*) as total, sum(total) as total_sum'))
                    ->groupBy('date')
                    ->get();

            }


            // Graph calculations
            $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
            $graph_one_order_values = $ordersQ->pluck('total')->toArray();
            $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();
//dd($graph_two_order_values);
            $price = OrderVendor::where('shop_id', session()->get('current_shop_domain'))->sum('product_price');

            $cost =  number_format($price, 2);

            $expenses_sum = Expense::where('shop_id', session()->get('current_shop_domain'))->sum('usd_price');
            $shipping_sum = OrderFulfillment::where('shop_id', session()->get('current_shop_domain'))->sum('shipping_price_usd');

            return view('products.reports')->with([
                'orders_sum' => $orders_total_price,
                'graph_one_labels' => $graph_one_order_dates,
                'graph_one_values' => $graph_one_order_values,
                'graph_two_values' => $graph_two_order_values,
//            'top_products_stores' => $top_products_stores,
                'cost' => $cost,
                'expenses_sum' => $expenses_sum,
                'shipping_sum' => $shipping_sum,
            ]);


        }elseif($shop_type['shop_type'] == "shopify"){

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
                $orders_total_price = ShopifyOrder::where('shop_id', session()->get('current_shop_domain'))->sum('total_price');

                $ordersQ = DB::table('shopify_orders')
                    ->select(DB::raw('DATE(processed_at) as date'), DB::raw('count(*) as total, sum(total_price) as total_sum'))
                    ->groupBy('date')
                    ->get();

            }


            // Graph calculations
            $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
            $graph_one_order_values = $ordersQ->pluck('total')->toArray();
            $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();
//        dd($graph_one_order_dates);


            // Vendor cost calculation
            $price = 0;

//        foreach (LineItem::whereNotNull('vendor')->get() as $item) {
//            $vendor = $item->vendor;
//                $vendors = json_decode($vendor);
//                foreach ($vendors as $vendor) {
//                    $vendor_details = ProductVendorDetail::where('id', $vendor)->first();
//                    $price += $vendor_details->cost;
//                }
//        }

            $price = OrderVendor::sum('product_price');

            $cost =  number_format($price, 2);

            $expenses_sum = Expense::where('shop_id', session()->get('current_shop_domain'))->sum('usd_price');
            $shipping_sum = OrderFulfillment::sum('shipping_price_usd');

            return view('products.reports')->with([
                'orders_sum' => $orders_total_price,
                'graph_one_labels' => $graph_one_order_dates,
                'graph_one_values' => $graph_one_order_values,
                'graph_two_values' => $graph_two_order_values,
//            'top_products_stores' => $top_products_stores,
                'cost' => $cost,
                'expenses_sum' => $expenses_sum,
                'shipping_sum' => $shipping_sum,
            ]);
        }
    }

    public static function setDate($d) {
        $str = $d;
        $date = strtotime($str);
        return date('d/M/Y ', $date);
    }

    public function getLogs(Request $request) {

        if ($request->has('type_search') && $request->has('role_search')) {
            $logs = Log::where('user_id', $request->input('role_search'))->where('type', 'LIKE', '%' . $request->input('type_search') . '%')->orderBy('updated_at', 'DESC')->paginate(20);
        }
        else if ($request->has('role_search')) {
            $logs = Log::where('user_id', $request->input('role_search'))->orderBy('updated_at', 'DESC')->paginate(20);
        }
        else if ($request->has('type_search')) {
            $logs = Log::where('type', 'LIKE', '%' . $request->input('type_search') . '%')->orderBy('updated_at', 'DESC')->paginate(20);
        }
        else {

            $logs = Log::where('shop_id', session()->get('current_shop_domain'))->orderBy('created_at', 'DESC')->paginate(30);
        }
//        dd($logs);
        return view('shops.log')->with('logs', $logs)->with('users', User::all());
    }

    public function deleteWebhooks() {
        $api = ShopsController::config();
        $response = $api->rest('GET', '/admin/webhooks.json', null, [], true);

        $webhook_ids = [];
        foreach ($response['body']['webhooks'] as $webhook) {
            array_push($webhook_ids, $webhook->id);
        }

        foreach ($webhook_ids as $id) {
            $api->rest('DELETE', '/admin/webhooks/'.$id.'.json');
        }

    }


    public function createWebhooks(Request $request) {
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){

            $current_shop_domain = Shop::where('id', session()->get('current_shop_domain'))->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();
            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);
            $data = [
                'name' => 'Order updated',
                'topic' => 'order.updated',
                'delivery_url' => 'https://nitesh-corp.com/get/webhooks'
            ];
            dd($woocommerce->get('webhooks/200'));
            dd('ok');
            //        print_r($woocommerce->get('webhooks/142'));
        }elseif($shop_type['shop_type'] == "shopify"){
            $api = ShopsController::config();

            $data = [
                "webhook"=> [
                    "topic"=> "orders/create",
                    "address"=> "https://nitesh-corp.com/webhook/orders-create",
                    "format"=> "json",
                ]
            ];
            $api->rest('POST', '/admin/webhooks.json', $data, [], true);
            $data = [];

            $data = [
                "webhook"=> [
                    "topic"=> "orders/updated",
                    "address"=> "https://nitesh-corp.com/webhook/orders-update",
                    "format"=> "json",
                ]
            ];
            $api->rest('POST', '/admin/webhooks.json', $data, [], true);
            $data = [];

            $data = [
                "webhook"=> [
                    "topic"=> "products/create",
                    "address"=> "https://nitesh-corp.com/webhook/products-create",
                    "format"=> "json",
                ]
            ];
            $api->rest('POST', '/admin/webhooks.json', $data, [], true);
            $data = [];

            $data = [
                "webhook"=> [
                    "topic"=> "products/update",
                    "address"=> "https://nitesh-corp.com/webhook/products-update",
                    "format"=> "json",
                ]
            ];
            $api->rest('POST', '/admin/webhooks.json', $data, [], true);
            $data = [];
        }

    }

    public function getWebhooks() {
        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){

            $current_shop_domain = Shop::where('id', session()->get('current_shop_domain'))->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();
            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);
            $data = [
                'name' => 'Order updated',
                'topic' => 'order.updated',
                'delivery_url' => 'https://nitesh-corp.com/woocommerce/orders-create'
            ];
            dd($woocommerce->post('webhooks',$data));
            dd('ok');
            //        print_r($woocommerce->get('webhooks/142'));
        }elseif($shop_type['shop_type'] == "shopify") {
            $api = ShopsController::config();
            $response = $api->rest('GET', '/admin/webhooks.json', null, [], true);
            dd($response);
        }
    }

    public function productCreateWebhook(Request $request){
//        $input = file_get_contents('php://input');
//        $product = json_decode($input, true);
//        $this->createProduct($product);

        // For Testing if webhook is working
        $webhook = new Webhook();
        $webhook->content = 'aa';
        $webhook->save();
    }

    public function orderCreateWebhook(Request $request){
//        $input = file_get_contents('php://input');
//        $order = json_decode($input, true);
//        $this->createOrder($order);
        Storage::disk('public')->put('check.txt', json_encode($request->all()));


        $new = new ErrorLog();
        $new->message = json_encode($request->all());
        $new->save();

//        $order_update = new WordpressController();
//        $orders = $request->all();
        try {

            $wordpress_order = WordpressOrder::where('wordpress_order_id', $request->id)->first();

            $current_shop_domain = Shop::where('id', $wordpress_order->shop_id)->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();
            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

            $end_lines=[];

            foreach (json_decode(json_encode($request->line_items),true) as $line_item){
                $variation_id = $line_item['variation_id'];
                $product_id = $line_item['product_id'];

                if($variation_id != null  && $product_id!= null){

                    $products = $woocommerce->get('products/'.$product_id);

                    $variations = $woocommerce->get('products/'.$products->id.'/variations/'.$variation_id);

                    $product_images_array = $products->images;

                    foreach ($product_images_array as $product_image){

                        if( $product_image->id === $variations->image->id){
                            $line_item['image']=$variations->image->src;
                        }elseif($product_image->id != null){
                            $line_item['image']=$product_image->src;

                        }else{
                            $line_item['image']= "null";
                        }
                    }
                }elseif ($product_id!= 0 || $variation_id == 0){

                    $products = $woocommerce->get('products/'.$product_id);
                    $product_images_array = $products->images;

                    foreach ($product_images_array as $product_image){
                        $line_item['image']=$product_image->src;
                    }
                }elseif ($product_id == 0){
                    $line_item['image']= "null";
                }
                array_push($end_lines, $line_item);
            }

            $end_lines=json_decode(json_encode($end_lines),FALSE);

            if(isset($wordpress_order) ){
                $new = new ErrorLog();
                $new->message = $wordpress_order;
                $new->save();
                $wordpress_order->wordpress_order_id = $request->id;
                $wordpress_order->shop_id = $wordpress_order->shop_id;
                $wordpress_order->parent_id = $request->parent_id;
                $wordpress_order->number = $request->number;
                $wordpress_order->order_key = $request->order_key;
                $wordpress_order->created_via = $request->created_via;
                $wordpress_order->version = $request->version;
                $wordpress_order->status = $request->status;
                $wordpress_order->currency = $request->currency;
                $wordpress_order->created_at = Carbon::createFromTimeString($request->date_created)->format('Y-m-d H:i:s');
                $wordpress_order->updated_at = Carbon::createFromTimeString($request->date_modified)->format('Y-m-d H:i:s');
                $wordpress_order->discount_total = $request->discount_total;
                $wordpress_order->discount_tax = $request->discount_tax;
                $wordpress_order->shipping_total = $request->shipping_total;
                $wordpress_order->shipping_tax = $request->shipping_tax;
                $wordpress_order->cart_tax = $request->cart_tax;
                $wordpress_order->total = $request->total;
                $wordpress_order->total_tax = $request->total_tax;
                $wordpress_order->prices_include_tax = $request->prices_include_tax;
                $wordpress_order->customer_id = $request->customer_id;
                $wordpress_order->customer_ip_address = $request->customer_ip_address;
                $wordpress_order->customer_user_agent = $request->customer_user_agent;
                $wordpress_order->customer_note = $request->customer_note;
                $wordpress_order->billing = json_encode($request->billing);
                $wordpress_order->shipping = json_encode($request->shipping);
                $wordpress_order->payment_method = $request->payment_method;
                $wordpress_order->payment_method_title = $request->payment_method_title;
                $wordpress_order->transaction_id = $request->transaction_id;
                $wordpress_order->date_paid = $request->date_paid;
                $wordpress_order->date_completed = $request->date_completed;
                $wordpress_order->cart_hash = $request->cart_hash;
                $wordpress_order->meta_data = json_encode($request->meta_data);
                if(isset($request->line_items)){
                    $wordpress_order->line_items = json_encode($end_lines);
                }

//                    $wordpress_order->line_items = json_encode($end_lines);
////                        dd($end_lines);
//                    foreach ($end_lines as $line_item){
//
//                        $line_item_save = WordpressLineItem::where('shop_id', session()->get('current_shop_domain'))->where('id', $line_item->id)->first();
//
//                        if($line_item_save === null){
//                            $line_item_save = new WordpressLineItem();
//                        }
//                        $line_item_save->id = $line_item->id;
//                        $line_item_save->shop_id = session()->get('current_shop_domain');
//                        $line_item_save->wordpress_order_id = $request->id;
//                        $line_item_save->wordpress_product_id = $line_item->product_id;
//                        $line_item_save->wordpress_variation_id = $line_item->variation_id;
//                        $line_item_save->name = $line_item->name;
//                        $line_item_save->quantity = $line_item->quantity;
//                        $line_item_save->sku = $line_item->sku;
//                        $line_item_save->meta_data = json_encode($line_item->meta_data);
//                        $line_item_save->taxes = json_encode($line_item->taxes);
//                        $line_item_save->total = $line_item->total;
//                        $line_item_save->total_tax = $line_item->total_tax;
//                        $line_item_save->subtotal = $line_item->subtotal;
//                        $line_item_save->subtotal_tax = $line_item->subtotal_tax;
//                        $line_item_save->tax_class = $line_item->tax_class;
//                        if(isset($line_item->image) && $line_item->image != ""){
//                            $line_item_save->image = $line_item->image;
//                        }
//
//                        $line_item_save->save();
//                    }
                $wordpress_order->tax_lines = json_encode($request->tax_lines);
                $wordpress_order->shipping_lines = json_encode($request->shipping_lines);
                $wordpress_order->fee_lines = json_encode($request->fee_lines);
                $wordpress_order->coupon_lines = json_encode($request->coupon_lines);
                $wordpress_order->refunds = json_encode($request->refunds);
                $wordpress_order->currency_symbol = $request->currency_symbol;
                $wordpress_order->links = json_encode($request->_links);

                $wordpress_order->update();
                return redirect()->back()->with('success', 'Wordpress Order Sync Successfully !');
            }
            else{
                return redirect()->back()->with('error', 'Orders not Found !');
            }

        } catch (\Exception $exception)
        {
            $new = new ErrorLog();
            $new->message = $exception->getMessage();
            $new->save();
        }

//        foreach ($requests as $order){

//        }

//        dd($request->all());
        $webhook = new Webhook();
        $webhook->content = 'aa';
        $webhook->save();

    }



    public function addTracking(Request $request) {

        if(Tracking::count() > 0) {
            Tracking::truncate();
        }
        $tracking = new Tracking();
        $tracking->tracking_number = $request->tracking_number;
        $tracking->tracking_url = $request->tracking_url;
        $tracking->tracking_company = $request->shipping_carrier;
        $tracking->location_id = '8749154419';
        $tracking->save();

        return response()->json(['data'=> 'success', 'tracking'=> $tracking]);
    }


    public function fulfillOrders(Request $request) {

        $shop_type =Shop::where('id', session()->get('current_shop_domain'))->select('shop_type', 'id')->first();

        if($shop_type['shop_type'] == "wordpress"){

            $orders = $request->orders;

            foreach ($orders as $id) {

                $order_fulfilled_update = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('wordpress_order_id', $id)->first();
                $order_fulfilled_update->status = 'completed';
                $order_fulfilled_update->save();

                DB::table('logs')->insert([
                    'shop_id' => session()->get('current_shop_domain'),
                    'user_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role,
                    'attempt_time' => Carbon::now()->toDateTimeString(),
                    'attempt_location_ip' => $request->getClientIp(),
                    'type' => 'Order Status Changed',
                    'shopify_order_id' => $order_fulfilled_update->id
                ]);

                $current_shop_domain = Shop::where('id', session()->get('current_shop_domain'))->pluck('shop_domain')->first();
                $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();
                $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);
                $data = [
                    'status' => 'completed',
                ];
                $response = $woocommerce->put('orders/'.$id, $data);

//                if($response['errors']) {
//                    dd($response);
//                    return redirect(route('admin.orders'))->with('error', 'Request cannot be proceed for order #'.$order->number);
//                }
            }
            return redirect(route('admin.orders'))->with('success', 'Orders Fulfilled Successfully!');

        }elseif($shop_type['shop_type'] == "shopify"){
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
                    'user_role' => Auth::user()->role,
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

    public function addOrderShippingPrice(Request $request, $id) {
        $order = ShopifyOrder::find($id);

        if($request->shipping_currency == 'usd') {
            ShippingPrice::create([
                'shipping_price' => $request->shipping_price,
                'shipping_currency' => $request->shipping_currency,
                'shopify_order_id' => $id,
            ]);
        }
        else{
            ShippingPrice::create([
                'shipping_price' => ((double) $request->shipping_price) * 6.6,
                'shipping_currency' => $request->shipping_currency,
                'shopify_order_id' => $id,
            ]);
        }

        Log::create([
            'user_id' => Auth::user()->id,
            'user_role' => Auth::user()->role,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Order Shipping Price added',
            'shopify_order_id' => $order->id
        ]);


        return redirect()->back()->with('success', 'Shipping Price added sucessfully!');
    }

    public function printOrderShipping(Request $request, $id) {

        dd($request->all());
        $line_items = LineItem::whereIn('id', $request->item_id)->get()->toArray();

        $items = [];
        foreach ($line_items as $index => $item) {
            $item['temp_qty'] =  $request->item_fulfill_quantity[$index];
            array_push($items, $item);
        }


        return view('orders.print')->with('order', ShopifyOrder::find($id))->with('items', $items);
    }

    public function showLineImages($id) {
        $item = LineItem::find($id);

        $images = $item->shopify_variant->shopify_product->product_images;

        return view('orders.line_images')->with('images',$images)->render();

    }
    public function showWordpressLineImages($line_item_product_id) {

        $product_image = WordpressProduct::where('wordpress_product_id', $line_item_product_id)->get();
//dd($product_image);
        $images = [];
        foreach($product_image as $img){
//            dd(json_decode($img->images));
            array_push($images, json_decode($img->images));
        }

        $images = json_decode(json_encode($images),true);
//        dd($images);
        return view('orders.wordpress_line_images')->with('images',$images)->render();

    }

}
