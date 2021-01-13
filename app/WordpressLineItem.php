<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordpressLineItem extends Model
{
    public function getVendorChkAttribute() {
        $varient = $this->shopify_variant;
//        dd($varient);
        $vendor_details = null;

        if($varient) {
            $product = $varient->shopify_product;
            $vendor_details = $product->product_vendor_details->count();

            if($vendor_details) {
                return true;
            }
            else{
                return false;
            }
        }
    }
}
