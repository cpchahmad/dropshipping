<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
      'product_id',
      'variant_title',
      'variant_price',
      'variant_qty',
      'variant_sku',
      'barcode',
      'image'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
