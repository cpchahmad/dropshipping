<?php

namespace App\Http\Controllers;

use App\LoginDetails;
use App\Product;
use App\ProductImage;
use App\ProductVendorDetail;
use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyProduct;
use App\ShopifyVarient;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function getProducts() {
        $products = ShopifyProduct::simplePaginate(20);
        $prods = Product::simplePaginate(20);
        $vendors = Vendor::all();


        return view('products.new-index')->with('products',$products)->with('prods',$prods)->with('vendors', $vendors);
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
        $customers = ShopifyCustomer::simplePaginate(20);
        return view('customers.index')->with('customers', $customers);

    }

    public function getOrders() {

        $orders = ShopifyOrder::simplePaginate(20);

        return view('orders.new-index')->with('orders', $orders);

    }

    public static function storeOrders() {
        $api = ShopsController::config();
        $orders = [];

        $response = $api->rest('GET', '/admin/orders/count.json', null, [],true);


        if(!$response['errors']) {
            $count = $response['body']['container']['count'];
            $iterations = ceil($count / 50);
            $next = '';


            for ($i = 1; $i <= $iterations; $i++) {
                if ($i == 1) {
                    $order_response = $api->rest('GET', '/admin/orders.json');
                } else {
                    $order_response = $api->rest('GET', '/admin/orders.json', ['page_info' => $next]);
                }

                if(!$order_response['errors']) {
                    if($order_response['link'] != null){
                        $next = $order_response['link']['next'];
                    }

                    $orders =  array_merge($orders,$order_response['body']['container']['orders']);

                }
            }
        }


        foreach ($orders as $order) {
            $o = ShopifyOrder::find($order['id']);

            if($o === null) {
                $o = new ShopifyOrder();

                if(array_key_exists("shipping_address",$order)) {
                    $o->shipping_address = json_encode($order['shipping_address']);
                }

                if(array_key_exists("billing_address",$order)) {
                    $o->billing_address = json_encode($order['billing_address']);
                }

                if(array_key_exists("customer",$order)) {
                    $o->customer = json_encode($order['customer']);
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
                $o->processed_at = $order['processed_at'];
                $o->line_items = json_encode($order['line_items']);
                $o->line_items = json_encode($order['line_items']);
                $o->shipping_lines = json_encode($order['shipping_lines']);

                $o->notes = $order['note'];
                $o->save();
            }
        }

    }

    public static function storeCustomers() {
        $api = ShopsController::config();
        $customers = [];

        $response = $api->rest('GET', '/admin/customers/count.json', null, [],true);


        if(!$response['errors']) {
            $count = $response['body']['container']['count'];
            $iterations = ceil($count / 50);
            $next = '';



            for ($i = 1; $i <= $iterations; $i++) {
                if ($i == 1) {
                    $customer_response = $api->rest('GET', '/admin/customers.json');
                } else {
                    $customer_response = $api->rest('GET', '/admin/customers.json', ['page_info' => $next]);
                }

                if(!$customer_response['errors']) {
                    if($customer_response['link'] != null){
                        $next = $customer_response['link']['next'];
                    }
                    $customers =  array_merge($customers,$customer_response['body']['container']['customers']);
                }
            }

        }


        foreach ($customers as $customer) {
            $c = ShopifyCustomer::find($customer['id']);

            if($c === null) {
                $c = new ShopifyCustomer();

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
                $c->addresses = json_encode($customer['addresses']);
                $c->default_address = json_encode($customer['default_address']);
                $c->save();
            }
        }

    }
    public static function storeProducts() {
        $api = ShopsController::config();
        $products = [];

        $response = $api->rest('GET', '/admin/products/count.json', null, [],true);


        if(!$response['errors']) {
            $count = $response['body']['container']['count'];
            $iterations = ceil($count / 50);
            $next = '';


            for ($i = 1; $i <= $iterations; $i++) {
                if ($i == 1) {
                    $product_response = $api->rest('GET', '/admin/products.json');
                } else {
                    $product_response = $api->rest('GET', '/admin/products.json', ['page_info' => $next]);
                }

                if(!$product_response['errors']) {
                    if($product_response['link'] != null){
                        $next = $product_response['link']['next'];
                    }

                    $products =  array_merge($products,$product_response['body']['container']['products']);

                }
            }
        }

        foreach ($products as $product) {


            $p = ShopifyProduct::find($product['id']);
            if($p === null){
                $p = new ShopifyProduct();
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

        }


    }

    public function addVendorForProduct(Request $request, $id) {

        $vendor_id_array = [];
        $product_price_array = [];
        $product_link_array = [];
        $notes_array = [];

        $vendor_id_array = array_merge($vendor_id_array, $request->vendor_id);
        $product_price_array = array_merge($product_price_array, $request->product_price);
        $product_link_array = array_merge($product_link_array, $request->product_link);
        $notes_array = array_merge($notes_array, $request->product_notes);

        for($i =0; $i< count($vendor_id_array); $i++) {

            $vendor = ProductVendorDetail::where('shopify_product_id', $id)->where('vendor_id', $vendor_id_array[$i])->first();
            if($vendor == null) {
                ProductVendorDetail::create([
                    'shopify_product_id' => $id,
                    'vendor_id' =>  $vendor_id_array[$i],
                    'product_price' => $product_price_array[$i],
                    'product_link' => $product_link_array[$i],
                    'notes' => $notes_array[$i],
                ]);
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
                return redirect()->back()->with('error', 'Nothing new to add!');
            }


        }

        return redirect()->back()->with('success', 'Vendors added successfully!');

    }

    public function getUsers() {
        $users = User::whereHas('roles', function ($query) {
            return $query->where('name','!=', 'admin');
        })->get();

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

            $api = ShopsController::config();
            $fulfillment_array_to_be_passed = [
                "fulfillment"=> [
                    "tracking_number"=> $request->tracking_number,
                    "tracking_url"=> $request->tracking_url,
                    "tracking_company"=> $request->shipping_carrier,
                    "location_id" => '6122438719'
                ]
              ];

            $response = $api->rest('POST', 'admin/orders/'.$order->id.'/fulfillments.json', $fulfillment_array_to_be_passed, [],true);

            if(!$response['errors']) {
                return redirect()->back()->with('success', 'Order status changed successfully!');
            }
            else {
                return redirect()->back()->with('error', 'Request cannot be proceed');
            }

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
        $products = Product::where('outsource_id', $id)->simplePaginate(10);
        $last_product = Product::where('outsource_id', $id)->orderBy('created_at', 'desc')->first();
        $login_details = LoginDetails::where('user_id', $id)->get();

        return view('shops.show-user')->with('user', $user)->with('products', $products)->with('product', $last_product)->with('details', $login_details);
    }




}
