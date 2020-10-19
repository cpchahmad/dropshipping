<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVendorDetail extends Model
{
    protected $fillable = [
      'shopify_product_id',
      'name',
      'url',
      'cost',
      'moq',
      'leads_time',
    ];





    public function getVendorNameAttribute() {
        $vendor_id = $this->vendor_id;

        $vendor = Vendor::find($vendor_id);

        return $vendor->name;
    }

    public function getCheckboxAttribute() {
        if(in_array($this->id, OrderVendor::all()->pluck('vendor_id')->toArray())) {
            return true;
        }
        else {
            return false;
        }
    }



}
