<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyCustomer extends Model
{
    public function shopify_orders() {
        return $this->hasMany(ShopifyOrder::class, 'user_id');
    }
}
