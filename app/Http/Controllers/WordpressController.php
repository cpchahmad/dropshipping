<?php

namespace App\Http\Controllers;

use App\Shop;
use App\WordpressLineItem;
use App\WordpressOrder;
use App\WordpressProduct;
use App\WordpressProductVariation;
use App\WorpressLineItem;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WordpressController extends Controller
{
    public function sync_wordpress_product($id){
        $variations = '';
        $current_shop_type = Shop::where('id', $id)->pluck('shop_type')->first();

        if($current_shop_type == 'wordpress'){

//            https://website.com/wc-api/v2/products?filter[limit]=-1&consumer_key=ck_1759xxx&consumer_secret=cs_49ecxxx

//            $woocommerce = new Client(
//		 'https://website.com',
//		 $this->wc_consumer_key,
//		 $this->wc_consumer_secret,
//		  [
//	            'wp_api' => true,
//		    'version' => 'wc/v2',
//	            'query_string_auth' => true
//		 ]
//	       );
//$param = array( 'filter[limit]' => '-1' );
//$product_list = $woocommerce->get( 'products', $param );

            $current_shop_domain = Shop::where('id', $id)->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();

            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

            try {
                $page = 1;
                $products = [];
                $all_products = [];
                do{
                    try {
                        $products = $woocommerce->get('products',['per_page' => 100, 'page' => $page]);
                    }catch(HttpClientException $e){
                        die("Can't get products: $e");
                    }
                    $all_products = array_merge($all_products,$products);
                    $page++;
                } while (count($products) > 0);

                $products =  json_decode(json_encode($all_products), FALSE);
                if($products != null) {
                    foreach ($products as $product) {

                        $wordpress_product = WordpressProduct::where('shop_id', $wordpress_shop->id)->where('wordpress_product_id', $product->id)->first();
                        if ($wordpress_product === null) {
                            $wordpress_product = new WordpressProduct();
                        }

                        $wordpress_product->wordpress_product_id = $product->id;
                        $wordpress_product->shop_id = $wordpress_shop->id;
                        $wordpress_product->name = $product->name;
                        $wordpress_product->slug = $product->slug;
                        $wordpress_product->permalink = $product->permalink;
                        if(isset($product->date_created)){
                            $wordpress_product->created_at = Carbon::createFromTimeString($product->date_created)->format('Y-m-d H:i:s');
                        }else{
                            $wordpress_product->created_at = Carbon::now()->format('Y-m-d H:i:s' );
                        }
                        if(isset($product->updated_at)){
                            $wordpress_product->updated_at = Carbon::createFromTimeString($product->date_modified)->format('Y-m-d H:i:s');
                        }else{
                            $wordpress_product->updated_at = Carbon::now()->format('Y-m-d H:i:s' );
                        }
                        $wordpress_product->type = $product->type;
                        $wordpress_product->status = $product->status;
                        $wordpress_product->featured = $product->featured;
                        $wordpress_product->catalog_visibility = $product->catalog_visibility;
                        $wordpress_product->description = $product->description;
                        $wordpress_product->short_description = $product->short_description;
                        $wordpress_product->sku = $product->sku;
                        $wordpress_product->price = $product->price;
                        $wordpress_product->regular_price = $product->regular_price;
                        $wordpress_product->sale_price = $product->sale_price;
                        $wordpress_product->date_on_sale_from = $product->date_on_sale_from;
                        $wordpress_product->date_on_sale_to = $product->date_on_sale_to;
                        $wordpress_product->price_html = $product->price_html;
                        $wordpress_product->on_sale = $product->on_sale;
                        $wordpress_product->purchasable = $product->purchasable;
                        $wordpress_product->total_sales = $product->total_sales;
                        $wordpress_product->downloadable = $product->downloadable;
                        $wordpress_product->downloads = json_encode($product->downloads);
                        $wordpress_product->download_limit = $product->download_limit;
                        $wordpress_product->download_expiry = $product->download_expiry;
                        $wordpress_product->external_url = $product->external_url;
                        $wordpress_product->tax_status = $product->tax_status;
                        $wordpress_product->tax_class = $product->tax_class;
                        $wordpress_product->manage_stock = $product->manage_stock;
                        $wordpress_product->stock_quantity = $product->stock_quantity;
                        $wordpress_product->stock_status = $product->stock_status;
                        $wordpress_product->backorders = $product->backorders;
                        $wordpress_product->backorders_allowed = $product->backorders_allowed;
                        $wordpress_product->backordered = $product->backordered;
                        $wordpress_product->sold_individually = $product->sold_individually;
                        $wordpress_product->weight = $product->weight;
                        $wordpress_product->dimensions = json_encode($product->dimensions);
                        $wordpress_product->shipping_required = $product->shipping_required;
                        $wordpress_product->shipping_taxable = $product->shipping_taxable;
                        $wordpress_product->shipping_class = $product->shipping_class;
                        $wordpress_product->shipping_class_id = $product->shipping_class_id;
                        $wordpress_product->reviews_allowed = $product->reviews_allowed;
                        $wordpress_product->average_rating = $product->average_rating;
                        $wordpress_product->rating_count = $product->rating_count;
                        $wordpress_product->related_ids = json_encode($product->related_ids);
                        $wordpress_product->upsell_ids = json_encode($product->upsell_ids);
                        $wordpress_product->cross_sell_ids = json_encode($product->cross_sell_ids);
                        $wordpress_product->parent_id = $product->parent_id;
                        $wordpress_product->purchase_note = $product->purchase_note;
                        $wordpress_product->categories = json_encode($product->categories);
                        $wordpress_product->tags = json_encode($product->tags);
                        $wordpress_product->images = json_encode($product->images);
                        $wordpress_product->attributes = json_encode($product->attributes);
                        $wordpress_product->default_attributes = json_encode($product->default_attributes);
                        $wordpress_product->variations = json_encode($product->variations);
                        if(isset($product->variations)){
                            foreach ($product->variations as $variation){
                                $variations = $woocommerce->get('products/'.$product->id.'/variations/'.$variation);

                                $wordpress_product_variation = WordpressProductVariation::where('shop_id', $wordpress_shop->id)->where('wordpress_variation_id', $variation)->first();
                                if ($wordpress_product_variation === null) {
                                    $wordpress_product_variation = new WordpressProductVariation();
                                }

                                $wordpress_product_variation->wordpress_product_id = $product->id;
                                $wordpress_product_variation->shop_id = $wordpress_shop->id;
                                $wordpress_product_variation->wordpress_variation_id = $variations->id;
                                if(isset($variations->date_created)){
                                    $wordpress_product_variation->created_at = Carbon::createFromTimeString($variations->date_created)->format('Y-m-d H:i:s');
                                }else{
                                    $wordpress_product_variation->created_at = Carbon::now()->format('Y-m-d H:i:s' );
                                }
                                if(isset($variations->updated_at)){
                                    $wordpress_product_variation->updated_at = Carbon::createFromTimeString($variations->date_modified)->format('Y-m-d H:i:s');
                                }else{
                                    $wordpress_product_variation->updated_at = Carbon::now()->format('Y-m-d H:i:s' );
                                }
                                $wordpress_product_variation->description = $variations->description;
                                $wordpress_product_variation->permalink = $variations->permalink;
                                $wordpress_product_variation->sku = $variations->sku;
                                $wordpress_product_variation->price = $variations->price;
                                $wordpress_product_variation->regular_price = $variations->regular_price;
                                $wordpress_product_variation->sale_price = $variations->sale_price;
                                $wordpress_product_variation->date_on_sale_from = $variations->date_on_sale_from;
                                $wordpress_product_variation->date_on_sale_to = $variations->date_on_sale_to;
                                $wordpress_product_variation->on_sale = $variations->on_sale;
                                $wordpress_product_variation->status = $variations->status;
                                $wordpress_product_variation->purchasable = $variations->purchasable;
                                $wordpress_product_variation->virtual = $variations->virtual;
                                $wordpress_product_variation->downloadable = $variations->downloadable;
                                $wordpress_product_variation->downloads = json_encode($variations->downloads);
                                $wordpress_product_variation->download_limit = $variations->download_limit;
                                $wordpress_product_variation->download_expiry = $variations->download_expiry;
                                $wordpress_product_variation->tax_status = $variations->tax_status;
                                $wordpress_product_variation->tax_class = $variations->tax_class;
                                $wordpress_product_variation->manage_stock = $variations->manage_stock;
                                $wordpress_product_variation->stock_quantity = $variations->stock_quantity;
                                $wordpress_product_variation->stock_status = $variations->stock_status;
                                $wordpress_product_variation->backorders = $variations->backorders;
                                $wordpress_product_variation->backorders_allowed = $variations->backorders_allowed;
                                $wordpress_product_variation->backordered = $variations->backordered;
                                $wordpress_product_variation->weight = $variations->weight;
                                $wordpress_product_variation->dimensions = json_encode($variations->dimensions);
                                $wordpress_product_variation->shipping_class_id = $variations->shipping_class_id;
                                $wordpress_product_variation->image = json_encode($variations->image);
                                $wordpress_product_variation->attributes = json_encode($variations->attributes);
                                $wordpress_product_variation->menu_order = $variations->menu_order;
                                $wordpress_product_variation->meta_data = json_encode($variations->meta_data);
                                $wordpress_product_variation->links = json_encode($variations->_links);

                                $wordpress_product_variation->save();

                            }
                        }
                        $wordpress_product->grouped_products = json_encode($product->grouped_products);
                        $wordpress_product->menu_order = $product->menu_order;
                        $wordpress_product->meta_data = json_encode($product->meta_data);
                        $wordpress_product->links = json_encode($product->_links);
                        $wordpress_product->save();

                    }
                    return redirect()->back()->with('success', 'Wordpress Product Sync Successfully !');
                }else{
                    return redirect()->back()->with('error', 'Products not Found !');
                }
            }
            catch(HttpClientException $e) {
                $error_msg = $e->getMessage(); // Error message.
                $e->getRequest(); // Last request data.
                $e->getResponse(); // Last response data
                return redirect()->back()->with('error', $error_msg);
            }
        }
        elseif($current_shop_type == 'shopify') {

            $call_shopify_product = new AdminController();

            $call_shopify_product->storeProducts($id);

            return redirect()->back()->with('success', 'Shopidy Product Sync Successfully !');
        }
        else {
            return redirect()->back()->with('error', 'Product Sync Failed!');
        }
    }

    public function sync_wordpress_order($id){
        $current_shop_type = Shop::where('id', $id)->pluck('shop_type')->first();
//        Wordpress Order Sync
        if($current_shop_type == 'wordpress'){

            $current_shop_domain = Shop::where('id', $id)->pluck('shop_domain')->first();
            $wordpress_shop = Shop::where('shop_domain', $current_shop_domain)->first();

            $woocommerce = new Client($wordpress_shop->shop_domain, $wordpress_shop->api_key, $wordpress_shop->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

            try {
//                $orders = $woocommerce->get('orders');
                $page = 1;
                $orders = [];
                $all_orders = [];
                do{
                    try {
                        $orders = $woocommerce->get('orders',['per_page' => 100, 'page' => $page]);
                    }catch(HttpClientException $e){
                        die("Can't get products: $e");
                    }
                    $all_orders = array_merge($all_orders,$orders);
                    $page++;
                } while (count($orders) > 0);

                $orders =  json_decode(json_encode($all_orders), FALSE);

//                $this->worpress_store_order($orders);

                if($orders != null){
                    foreach ($orders as $order){
                        $this->wordpress_store_order($order, $woocommerce);
                    }
                    return redirect()->back()->with('success', 'Wordpress Order Sync Successfully !');
                }else{
                    return redirect()->back()->with('error', 'Orders not Found !');
                }

            }
            catch(HttpClientException $e) {
                $error_msg = $e->getMessage(); // Error message.
                $e->getRequest(); // Last request data.
                $e->getResponse(); // Last response data
                return redirect()->back()->with('error', 'Error:'.$error_msg);
            }
//            Shopify Order Sync
        }elseif($current_shop_type == 'shopify'){

            $call_shopify_order = new AdminController();

            $call_shopify_order->storeOrders($id);

            return redirect()->back()->with('success', 'Shopify Order Sync Successfully !');

        }
        else {
            return redirect()->back()->with('error', 'Shopify Order Can,t Sync !');
        }
    }

    public function wordpress_store_order($order, $woocommerce){

        $end_lines=[];
//        $order=json_decode(json_encode($order),FALSE);
//                        dd(json_decode(json_encode($order->line_items),true));
//        dd($order);
        foreach (json_decode(json_encode($order->line_items),true) as $line_item){
            $variation_id = $line_item['variation_id'];
            $product_id = $line_item['product_id'];
//                            dd($variation_id);
            if($variation_id != null  && $product_id!= null){
//                                $product_api_data = $shop->api()->rest('GET', '/admin/api/2020-10/products/'.$product_id.'.json')['body']['product'];
//                                $variant_api_data = $shop->api()->rest('GET', '/admin/api/2020-10/variants/'.$variant_id.'.json')['body']['variant'];
                $products = $woocommerce->get('products/'.$product_id);
//                                dd($products);
                $variations = $woocommerce->get('products/'.$products->id.'/variations/'.$variation_id);
//                                dd($products->src);
                $product_images_array = $products->images;
//                                dd($product_images_array);
                foreach ($product_images_array as $product_image){
//                                    dd($variations->image->id);
                    if( $product_image->id === $variations->image->id){
                        $line_item['image']=$variations->image->src;
                    }elseif($product_image->id != null){
                        $line_item['image']=$product_image->src;
//                                        dd($line_item['image']);
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
//                        dd($end_lines);
        $end_lines=json_decode(json_encode($end_lines),FALSE);
//                        dd($end_lines);
        $wordpress_order = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->where('wordpress_order_id', $order->id)->first();
        if($wordpress_order === null){
            $wordpress_order = new WordpressOrder();
        }
        $wordpress_order->wordpress_order_id = $order->id;
        $wordpress_order->shop_id = session()->get('current_shop_domain');
        $wordpress_order->parent_id = $order->parent_id;
        $wordpress_order->number = $order->number;
        $wordpress_order->order_key = $order->order_key;
        $wordpress_order->created_via = $order->created_via;
        $wordpress_order->version = $order->version;
        $wordpress_order->status = $order->status;
        $wordpress_order->currency = $order->currency;
        $wordpress_order->created_at = Carbon::createFromTimeString($order->date_created)->format('Y-m-d H:i:s');
        $wordpress_order->updated_at = Carbon::createFromTimeString($order->date_modified)->format('Y-m-d H:i:s');
        $wordpress_order->discount_total = $order->discount_total;
        $wordpress_order->discount_tax = $order->discount_tax;
        $wordpress_order->shipping_total = $order->shipping_total;
        $wordpress_order->shipping_tax = $order->shipping_tax;
        $wordpress_order->cart_tax = $order->cart_tax;
        $wordpress_order->total = $order->total;
        $wordpress_order->total_tax = $order->total_tax;
        $wordpress_order->prices_include_tax = $order->prices_include_tax;
        $wordpress_order->customer_id = $order->customer_id;
        $wordpress_order->customer_ip_address = $order->customer_ip_address;
        $wordpress_order->customer_user_agent = $order->customer_user_agent;
        $wordpress_order->customer_note = $order->customer_note;
        $wordpress_order->billing = json_encode($order->billing);
        $wordpress_order->shipping = json_encode($order->shipping);
        $wordpress_order->payment_method = $order->payment_method;
        $wordpress_order->payment_method_title = $order->payment_method_title;
        $wordpress_order->transaction_id = $order->transaction_id;
        $wordpress_order->date_paid = $order->date_paid;
        $wordpress_order->date_completed = $order->date_completed;
        $wordpress_order->cart_hash = $order->cart_hash;
        $wordpress_order->meta_data = json_encode($order->meta_data);
        $wordpress_order->line_items = json_encode($end_lines);
//                        dd($end_lines);
        foreach ($end_lines as $line_item){

            $line_item_save = WordpressLineItem::where('shop_id', session()->get('current_shop_domain'))->where('id', $line_item->id)->first();

            if($line_item_save === null){
                $line_item_save = new WordpressLineItem();
            }
            $line_item_save->id = $line_item->id;
            $line_item_save->shop_id = session()->get('current_shop_domain');
            $line_item_save->wordpress_order_id = $order->id;
            $line_item_save->wordpress_product_id = $line_item->product_id;
            $line_item_save->wordpress_variation_id = $line_item->variation_id;
            $line_item_save->name = $line_item->name;
            $line_item_save->quantity = $line_item->quantity;
            $line_item_save->sku = $line_item->sku;
            $line_item_save->meta_data = json_encode($line_item->meta_data);
            $line_item_save->taxes = json_encode($line_item->taxes);
            $line_item_save->total = $line_item->total;
            $line_item_save->total_tax = $line_item->total_tax;
            $line_item_save->subtotal = $line_item->subtotal;
            $line_item_save->subtotal_tax = $line_item->subtotal_tax;
            $line_item_save->tax_class = $line_item->tax_class;
            if(isset($line_item->image) && $line_item->image != ""){
                $line_item_save->image = $line_item->image;
            }

            $line_item_save->save();
        }
        $wordpress_order->tax_lines = json_encode($order->tax_lines);
        $wordpress_order->shipping_lines = json_encode($order->shipping_lines);
        $wordpress_order->fee_lines = json_encode($order->fee_lines);
        $wordpress_order->coupon_lines = json_encode($order->coupon_lines);
        $wordpress_order->refunds = json_encode($order->refunds);
        $wordpress_order->currency_symbol = $order->currency_symbol;
        $wordpress_order->links = json_encode($order->_links);

        $wordpress_order->save();

    }
}
