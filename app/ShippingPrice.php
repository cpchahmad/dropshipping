<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingPrice extends Model
{
    protected $fillable = [
      'shopify_order_id',
      'shipping_price',
      'shipping_currency'
    ];
}
