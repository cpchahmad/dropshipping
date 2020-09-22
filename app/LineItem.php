<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    public function shopify_order() {
        $this->belongsTo(ShopifyOrder::class, '');
    }

    public function getImgAttribute() {

        $varient = ShopifyVarient::find($this->variant_id);
        $image_src = "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
        if($varient){
            $image_id = $varient->image_id;
            $image = ProductImage::where('shopify_id', $image_id)->first();
            if($image){

                $image_src = $image->src;
            }
            else{
                $product_id = $varient->shopify_product_id;
                $image_src = $this->getImageAttribute($product_id);
            }
        }

        return $image_src;
    }

    public function getImageAttribute($id) {
        $product = ShopifyProduct::find($id);

        if($product->image == "null") {
            return "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
        }
        else{

            if($product->image == null) {

                dd('yes');
                return "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
            }
            else {
                $image = json_decode($product->image);
                return $image->src;
            }

        }
    }

    public function getVendorsAttribute() {
        $varient = ShopifyVarient::find($this->variant_id);
        $vendor_details = null;

        if($varient) {
            $product = $varient->shopify_product()->first();
            $vendor_details = ProductVendorDetail::where('shopify_product_id', $product->id)->get();

            if($vendor_details != null) {
                foreach ($vendor_details as $details) {
                    echo "
                        <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
                            <div class='row d-flex'>
                                <div class='mr-2'>
                                    <input type='checkbox' class='from-control' name='vendors[]' value='$details->id'>
                                    <input type='hidden' value='$details->shopify_product_id'>
                                    <input type='hidden' value='$details->vendor_id'>
                                </div>
                                <div class='mr-2'>
                                    $details->vendor_name
                                </div>
                                <div class='font-weight-bold mr-2'>
                                    <span class=>$".number_format($details->product_price, 2)."</span>
                                </div>
                                <div class='font-weight-bold'>
                                    <a href='$details->product_link' target='_blank'>Place Order</a>
                                </div>
                            </div>

                        </li>
                    ";
                }
            }
        }

    }

    public function getCostAttribute() {
        $vendor = $this->vendor;
        $price = null;

        if($vendor != null) {
            $vendors = json_decode($vendor);


            foreach ($vendors as $vendor) {
                $ven = Vendor::where('name', $vendor)->first();

                $vendor_details = ProductVendorDetail::where('vendor_id', $ven->id)->first();

                $price += $vendor_details->product_price;
            }
        }
        else {
            return null;
        }

        return number_format($price, 2);
    }

    public function getCostCheckAttribute() {
        $vendor = $this->vendor;

        if($vendor != null) {
            return true;
        }
        else {
            return false;
        }
    }
}
