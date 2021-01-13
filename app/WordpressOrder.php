<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordpressOrder extends Model
{
    public function shopify_order_notes() {
        return $this->hasMany(ShopifyOrderNote::class, 'shopify_order_id');
    }

    public function getNoteCheckAttribute() {

        if($this->customer_note == null && $this->shopify_order_notes->count() === 0) {
            return false;
        }
        else {
            return true;
        }
    }

    public function getStatusCheckAttribute() {
        if($this->status == 'completed') {
            return false;
        }
        else {
            return true;
        }
    }

    public function getFulCheckAttribute() {

        if(is_null($this->status)) {
            return true;
        }
        else{
            return false;
        }
    }
    public function getFulStatusAttribute() {

        if($this->status != "completed") {
            return true;
        }
        else{
            return false;
        }
    }


    public function getIsUnfulfilledAttribute() {
        if(is_null($this->status) || $this->status != "completed") {
            return true;
        }
        else{
            return false;
        }
    }

    public function getBgAttribute() {
        if($this->status == 'completed') {
            return "green";
        }
        else if($this->status == 'processing'){
            return "#008068";
        }
        else {
            return 'white';
        }
    }

    public function getColorAttribute() {
        if($this->status == 'completed') {
            return "white";
        }
        else if($this->status == 'processing'){
            return "white";
        }
        else {
            return '#575757';
        }
    }

    public function getFulfillmentAttribute() {
        if($this->status == 'completed') {
            return "Fulfilled";
        }
        else if($this->status == 'processing'){
            return "Partial";
        }
        else {
            return 'unfulfilled';
        }
    }

    public function getShipAddAttribute() {
        $address_obj = json_decode($this->shipping);
//dd($address_obj);
        if(is_null($address_obj)){
            return "No shipping address";
        }
        else {
            $address_obj = $address_obj;

            echo "
                <div class='d-flex flex-column'>
                     <span>$address_obj->first_name $address_obj->last_name</span>
                     <span>$address_obj->company</span>
                     <span>$address_obj->address_1</span>
                     <span>$address_obj->address_2</span>
                     <span>$address_obj->city $address_obj->postcode </span>
                     <span>$address_obj->country</span>
                </div>

            ";
        }
    }
    public function getBillAddressAttribute() {
        $address_obj = json_decode($this->shipping);
//dd($address_obj);
        if(is_null($address_obj)){
            return "No shipping address";
        }
        else {
            $address_obj = $address_obj;

            echo "
                <div class='d-flex flex-column'>
                     <span>$address_obj->first_name $address_obj->last_name</span>
                     <span>$address_obj->company</span>
                     <span>$address_obj->address_1</span>
                     <span>$address_obj->address_2</span>
                     <span>$address_obj->city $address_obj->postcode </span>
                     <span>$address_obj->country</span>
                </div>

            ";
        }
    }

    public function items() {
        return $this->hasMany(WordpressLineItem::class, 'wordpress_order_id', 'wordpress_order_id');
    }

    public function getVendorChkAttribute() {
//        $varient = $this->shopify_variant;
////        dd($varient);
//        $vendor_details = null;
//
//        if($varient) {
//            $product = $varient->shopify_product;
//            $vendor_details = $product->product_vendor_details->count();

        $vendor_details = WordpressLineItem::where('id', $this->id)->select('vendor')->first();
//        dd($vendor_details);
            if($vendor_details != null) {
                return true;
            }
            else{
                return false;
            }

    }


}
