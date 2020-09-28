<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
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
        return date('d/M/Y h:i:s', $date);
    }

    public function getItemAttribute() {
        if($this->type == "Product Updated" || $this->type == "Product Added") {
            $product = Product::find($this->shopify_product_id);

            echo "
                <a href='/admin/products?search=$product->title' target='_blank' style='font-size: 12px !important;'>
                    $product->title
                </a>
            ";
        }

        if($this->type == "Order Status Changed") {
            $order = ShopifyOrder::find($this->shopify_order_id);

            echo "
                <a href='/admin/orders?search=$order->name' target='_blank' style='font-size: 12px !important;'>
                    #$order->name
                </a>
            ";
        }


    }

    public function getLocationAttribute() {
        $ip = $this->attempt_location_ip;

        $data = \Location::get($ip);

        return $data->countryName .", ". $data->cityName;
    }

}
