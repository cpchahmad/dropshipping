<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'user_role',
        'shopify_product_id',
        'shopify_order_id',
        'type',
        'attempt_time',
        'attempt_location_ip',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getDateAttribute() {
        $str = $this->attempt_time;
        $date = strtotime($str);
        return date('M,d,Y h:i:s', $date);
    }

    public function getItemAttribute() {
//        dd($this->shopify_product_id);
        if($this->type == "Product Updated" || $this->type == "Product Added" || $this->type == "Product Vendor Added") {
            $product = Product::find($this->shopify_product_id);

            if($product !== null) {
                echo "
                <a href='/admin/products?search=$product->title' target='_blank' style='font-size: 12px !important; color: white!important;'>
                    $product->title
                </a>
            ";
            }

        }

        if(isset($order)){

            if($this->type == "Order Fulfilled" || $this->type == "Order Shipping Price added" || $this->type == "Order Notes Added" || $this->type == "Vendor added to order") {
                $order = ShopifyOrder::where('shop_id', session()->get('current_shop_domain'))->where('id', $this->shopify_order_id)->first();
                echo "
                        <a href='/admin/orders?search=$order->name' target='_blank' style='font-size: 12px !important; color: white!important;'>
                            #$order->name
                        </a>
                    ";
            }
        }


    }
//
//    public function getLocationAttribute() {
//        $ip = $this->attempt_location_ip;
//
//        $data = \Location::get($ip);
////dd($data);
//        return $data->countryName .", ". $data->cityName;
//    }

}
