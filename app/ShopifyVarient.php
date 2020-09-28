<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyVarient extends Model
{
    protected $fillable = [
       'id',
       'shopify_product_id',
       'title',
       'price',
       'sku',
       'position',
       'inventory_policy',
       'compare_at_price',
       'fulfillment_service',
       'inventory_management',
       'option1',
       'option2',
       'option3',
       'taxable',
       'barcode',
       'grams',
       'image_id',
       'weight',
       'weight_unit',
       'inventory_item_id',
       'inventory_quantity',
       'old_inventory_quantity',
       'requires_shipping'
    ];


    public function shopify_product() {
        return $this->belongsTo(ShopifyProduct::class);
    }

}
