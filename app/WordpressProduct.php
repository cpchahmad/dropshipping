<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordpressProduct extends Model
{
    public function wordpress_product_variations() {
        return $this->hasMany(WordpressProductVariation::class, 'wordpress_product_id', 'wordpress_product_id');
    }

    public function product_vendor_details() {
        return $this->hasMany(ProductVendorDetail::class);
    }
}
