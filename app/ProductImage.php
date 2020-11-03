<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'shopify_id',
        'product_id',
        'position',
        'alt',
        'width',
        'height',
        'src',
        'variant_ids'
    ];

    public function shopify_product() {
        return $this->belongsTo(ShopifyProduct::class);
    }

}
