<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'url'
    ];


    public function shopify_products() {
        return $this->belongsToMany(ShopifyProduct::class);
    }
}
