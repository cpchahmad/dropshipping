<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    protected $fillable = [
      'order_id',
      'total_line_item_price',
      'total_price',
      'currency',
      'name',
      'customer',
      'fulfillment_status',
      'line_items',
      'shipping_address',
      'billing_address',
      'processed_at',
    ];

    public function getDateAttribute() {
        $str = $this->processed_at;
        $date = strtotime($str);
        return date('d/M/Y h:i:s', $date);
    }

    public function shopify_customer() {
        return $this->belongsTo(ShopifyCustomer::class);
    }

    public function getCustomerNameAttribute() {
        $name_object = json_decode($this->customer);

        $first_name = $name_object->first_name;
        $last_name = $name_object->last_name;

        return $first_name.' '.$last_name;
    }

    public function getStatusAttribute() {
        return $this->fulfillment_status ? 'badge badge-success' : 'badge badge-warning';
    }
    public function getFulfillmentAttribute() {
        return $this->fulfillment_status ? 'Fulfilled' : 'Unfulfilled';
    }


    public function getPaymentStatusAttribute() {
        if($this->financial_status == "paid") {
            return 'badge badge-success';
        }
        else{
            return 'badge badge-warning';
        }
    }


    public function getBillAddressAttribute() {
        $address_obj = $this->billing_address;

        if(is_null($address_obj)){
            return "No billing address";
        }
        else {
            $address_obj = json_decode($address_obj);

            echo "
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">ADDRESS</h1> $address_obj->address1
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">CITY </h1> $address_obj->city
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">COUNTRY </h1> $address_obj->country
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">PROVINCE </h1> $address_obj->province
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">ZIP </h1> $address_obj->zip
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">PHONE </h1> $address_obj->phone
                 </div>
                 ";
        }
    }


    public function getShipAddressAttribute() {
        $address_obj = $this->shipping_address;

        if(is_null($address_obj)){
            return "No shipping address";
        }
        else {
            $address_obj = json_decode($address_obj);

            echo "
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">ADDRESS</h1> $address_obj->address1
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">CITY </h1> $address_obj->city
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">COUNTRY </h1> $address_obj->country
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">PROVINCE </h1> $address_obj->province
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">ZIP </h1> $address_obj->zip
                 </div>
                 <div class=\"block-header p-0\">
                        <h1 class=\"block-title\">PHONE </h1> $address_obj->phone
                 </div>
                 ";
        }
    }

    public function getShipAddAttribute() {
        $address_obj = $this->shipping_address;

        if(is_null($address_obj)){
            return "No shipping address";
        }
        else {
            $address_obj = json_decode($address_obj);
            echo "
                <div class='d-flex flex-column'>
                     <span>$address_obj->first_name</span>
                     <span>$address_obj->phone</span>
                     <span>$address_obj->address1</span>
                     <span>$address_obj->city</span>
                     <span>$address_obj->country</span>
                </div>

            ";
        }
    }

    public function getLineItemsCountAttribute() {
        $line_item_obj = json_decode($this->line_items);

        $line_items_count = count($line_item_obj);

        return $line_items_count;
    }

    public function getLineDetailsAttribute() {
        $line_item_obj = json_decode($this->line_items);

        $counter = 0;


        foreach ($line_item_obj as $item) {

            $varient = ShopifyVarient::find($item->variant_id);
            $image_src = null;

            if($varient){
                $image_id = $varient->image_id;
                $image = ProductImage::where('shopify_id', $image_id)->first();
                if($image){
                    $image_src = $image->src;
                }
                else{
                    $product_id = $varient->shopify_product_id;
                    $image_src = $this->getImgAttribute($product_id);
                }
            }

            if( $counter == count( $line_item_obj ) - 1) {
                echo "
                <div class='d-flex align-items-center py-2'>
                    <div>
                    <img src='$image_src' alt='No img' class=\"img-fluid\" style='width: 50px; height: auto;'>
                    </div>
                    <div class='ml-2 text-left'>
                        <span class=\"d-block font-weight-lighter\">$item->title</span>
                        <span class=\"d-block font-weight-lighter\">(SKU: $item->sku)</span>
                    </div>
                        <p class=\"d-block font-weight-bold ml-5 text-left\">x$item->quantity</p>

                </div>

            ";
            }
            else{
                echo "
                <div class='d-flex align-items-center border-bottom py-2'>
                    <div>
                    <img src='$image_src' alt='No img' class=\"img-fluid\" style='width: 50px; height: auto;'>
                    </div>
                    <div class='ml-2 text-left'>
                        <span class=\"d-block font-weight-lighter\">$item->title</span>
                        <span class=\"d-block font-weight-lighter\">(SKU: $item->sku)</span>
                    </div>
                        <p class=\"d-block font-weight-bold ml-5 text-left\">x$item->quantity</p>

                </div>

            ";
            }

            $counter++;

        }

    }

    public function getShippingMethodAttribute() {
        $shipping = json_decode($this->shipping_lines);


        if(count($shipping)> 0) {
            return $shipping[0]->code;
        }
        else {
            return 'not provided';
        }


    }

    public function getLineItemDetailsAttribute() {
        $line_item_obj = json_decode($this->line_items);


        foreach ($line_item_obj as $item) {

            $varient = ShopifyVarient::find($item->variant_id);
            $image_src = null;

            if($varient){
                $image_id = $varient->image_id;
                $image = ProductImage::where('shopify_id', $image_id)->first();
                if($image){
                    $image_src = $image->src;
                }
                else{
                    $image_src = 'null';
                }
            }

            echo "
             <tr>
                <td class='d-flex align-items-center'>
                    <div>
                    <img src='$image_src' alt='No img' class=\"img-fluid\" style='width: 120px; height: auto;'>
                    </div>
                    <div class='ml-2'>
                        <span class=\"d-block\">$item->title</span>
                        <span class=\"d-block\">$item->variant_title</span>
                        <span class=\"d-block\">$item->sku</span>
                    </div>
                </td>

                <td>
                    <span class=\"d-block\">$$item->price x $item->quantity</span>
                </td>

                <td>
                    <span class=\"d-block\">$".number_format($item->price * $item->quantity, 2)."</span>
                </td>
            </tr>
            ";
        }

    }

    public function getPaymentDetailsAttribute() {

        $line_item_obj = json_decode($this->line_items);


        $item_count = 0;
        $calculated_total = 0;
        $calculated_total_discount = 0;

        foreach ($line_item_obj as $item) {
            $calculated_total += $item->price;
            $calculated_total_discount += $item->total_discount;
            $item_count++;
        }

        $calculated_total = number_format($calculated_total,2);
        $calculated_total_discount = number_format($calculated_total_discount,2);
        $final_total = $calculated_total - $calculated_total_discount;
        $final_total = number_format($final_total, 2);


        echo "
            <div class=\"block-header\">
                <h1 class=\"block-title\">Subtotal <span class=\"ml-2 text-muted\"> $item_count item</span></h1>$$calculated_total
            </div>
            <div class=\"block-header\">
                <h1 class=\"block-title\">Discount </h1>$$calculated_total_discount
            </div>
            <div class=\"block-header\">
                <h1 class=\"block-title\">Tax <span class=\"ml-2 text-muted\"> gst 16%</span></h1>Not working
            </div>
            <div class=\"block-header\">
                <h1 class=\"block-title\">Total </h1>$final_total
            </div>
        ";
    }



    public function getPriceAttribute() {
        return number_format($this->total_price, 2);
    }

    public function getImgAttribute($id) {
        $product = ShopifyProduct::find($id);

        if($product->image == "null") {
            return 12;
        }
        else{
            $image = json_decode($product->image);
            return $image->src;
        }
    }
}
