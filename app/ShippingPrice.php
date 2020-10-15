<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingPrice extends Model
{
    protected $fillable = [
      'shopify_order_id',
      'shipping_price_usd',
      'shipping_price_rmb',
      'shipping_currency'
    ];
}
