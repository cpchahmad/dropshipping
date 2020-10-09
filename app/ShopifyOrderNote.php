<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyOrderNote extends Model
{
    protected $fillable = [
        'notes',
        'shopify_order_id'
    ];
}
