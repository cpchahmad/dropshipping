<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVendorDetail extends Model
{
    protected $fillable = [
      'shopify_product_id',
      'vendor_id',
      'product_price',
      'product_link',
      'notes'
    ];

    public function getVendorNameAttribute() {
        $vendor_id = $this->vendor_id;

        $vendor = Vendor::find($vendor_id);

        return $vendor->name;
    }
}
