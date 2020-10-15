<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'description', 'price', 'compare_price', 'outsource_id', 'weight', 'unit', 'quantity', 'tags', 'notes'];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function prod_images() {
        return $this->hasMany(ProdImage::class);
    }

    public function product_links() {
        return $this->hasMany(ProductLink::class);
    }

    public function product_vendor_details() {
        return $this->hasMany(ProductVendorDetail::class, 'shopify_product_id');
    }

    public function getImageAttribute() {
        $first_image = $this->prod_images()->first();

        if($first_image !=null) {
            return asset('storage').'/'.$first_image->image;
        }
        else{
            return "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
        }

    }



    public function getProductTagsAttribute() {
        return $this->tags;
    }

    public function varients() {
        return $this->hasMany(Variant::class);
    }

    public function getApprovedStatusAttribute() {
         if($this->approved== 0)
         {
             echo '<i class="fa fa-times text-danger ml-2"></i>';
         }
         else
         {
             echo '<i class="fa fa-check text-success ml-2"></i>';
         }

    }

    public function getTagArrayAttribute() {
        return explode(',', $this->tags);
    }

    public function getSourceNameAttribute() {
        return User::find($this->outsource_id)->name;
    }

    public function getVariantDetailsAttribute() {


        $varients = Variant::where('product_id',$this->id)->get();
        $counter = 0;

        if(count($varients) >0){
            foreach ($varients as $varient) {

                if($varient->image == null)
                    $image_src =  "https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png";
                else
                    $image_src = asset('storage').'/'.$varient->image;

                if( $counter == count( $varients ) - 1) {
                    echo "
                        <div class='d-flex align-items-center py-2'>
                            <div>
                            <img src=\"$image_src\" alt='No img' class=\"img-fluid hover-img\" style='width: 50px; height: auto;'>
                            </div>
                            <div class='ml-2 text-left'>
                                <span class=\"d-block font-weight-bold\" style=\"font-size: 14px;\">$varient->variant_title </span>
                                <span class=\"d-block\" style=\"font-size: 12px;\"><strong>SKU:</strong> $varient->variant_sku</span>
                                <span class=\"d-block\" style=\"font-size: 12px;\">$".number_format($varient->variant_price,2)."</span>
                            </div>
                        </div>
                    ";
                }
                else{
                    echo "
                        <div class='d-flex align-items-center border-bottom py-2'>
                            <div>
                            <img src=\"$image_src\" alt='No img' class=\"img-fluid hover-img\" style='width: 50px; height: auto;'>
                            </div>
                            <div class='ml-2 text-left'>
                                <span class=\"d-block font-weight-bold\" style=\"font-size: 14px;\">$varient->variant_title </span>
                                <span class=\"d-block\" style=\"font-size: 12px;\"><strong>SKU:</strong> $varient->variant_sku</span>
                                <span class=\"d-block\" style=\"font-size: 12px;\">$".number_format($varient->variant_price,2)."</span>
                            </div>
                        </div>
                    ";
                }

                $counter++;

            }
        }
    }
}
