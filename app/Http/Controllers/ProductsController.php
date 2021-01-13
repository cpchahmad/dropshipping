<?php

namespace App\Http\Controllers;

use App\Category;
use App\Log;
use App\Product;
use App\ProdImage;
use App\ProductLink;
use App\ProductVendorDetail;
use App\SubCategory;
use App\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index')->with('products', Product::where('shop_id', session()->get('current_shop_domain'))->where('outsource_id', auth()->user()->id)->simplePaginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        return view('products.create')->with('product', $product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
        ]);

        $product = new Product;
        $product->shop_id = session()->get('current_shop_domain') ;
        $product->title = $request->title ;
        $product->description = $request->description ;
        $product->outsource_id = auth()->user()->id ;
        $product->save();

        if($request->hasFile('images')) {
            foreach ($request->images as $image)
            {
                $image_url = $image->store('products');

                $product_image = new ProdImage;
                $product_image->shop_id = session()->get('current_shop_domain') ;
                $product_image->image = $image_url;
                $product_image->product_id = $product->id ;
                $product_image->save();
            }
        }

        if($request->has('link')) {
            foreach ($request->link as $lin)
            {
                if(!(is_null($lin))) {
                    $link = new ProductLink();
                    $link->shop_id = session()->get('current_shop_domain');
                    $link->link = $lin;
                    $link->product_id = $product->id;
                    $link->save();
                }
            }
        }

        if($request->has('vendor_name')) {
            $product_price_array = [];
            $product_link_array = [];
            $vendor_name_array = [];
            $moq_array = [];
            $lead_time_array = [];
            $weight_array = [];
            $length_array = [];
            $width_array = [];
            $height_array = [];
            $volume_array = [];


            $product_price_array = array_merge($product_price_array, $request->product_price);
            $product_link_array = array_merge($product_link_array, $request->product_link);
            $vendor_name_array = array_merge($vendor_name_array, $request->vendor_name);
            $moq_array = array_merge($moq_array, $request->moq);
            $lead_time_array = array_merge($lead_time_array, $request->leads_time);
            $weight_array = array_merge($weight_array, $request->weight);
            $length_array = array_merge($length_array, $request->length);
            $width_array = array_merge($width_array, $request->width);
            $height_array = array_merge($height_array, $request->height);


            for($i =0; $i< count($vendor_name_array); $i++) {

                if(!(is_null($vendor_name_array[$i]))) {
                    DB::table('product_vendor_details')->insert([
                        'shop_id' => session()->get('current_shop_domain'),
                        'shopify_product_id' => $product->id,
                        'name' =>  $vendor_name_array[$i],
                        'cost' => $product_price_array[$i],
                        'url' => $product_link_array[$i],
                        'moq' => $moq_array[$i],
                        'leads_time' => $lead_time_array[$i],
                        'weight' => $weight_array[$i],
                        'length' => $length_array[$i],
                        'width' => $width_array[$i],
                        'height' => $height_array[$i],
                        'volume' => ($length_array[$i] * $width_array[$i] * $height_array[$i])
                    ]);
                }

            }


            DB::table('logs')->insert([
                'shop_id' => session()->get('current_shop_domain'),
                'user_id' => Auth::user()->id,
                'attempt_time' => Carbon::now()->toDateTimeString(),
                'attempt_location_ip' => $request->getClientIp(),
                'type' => 'Product Vendor Added',
                'shopify_product_id' => $product->id
            ]);

        }




        DB::table('logs')->insert([
            'shop_id' => session()->get('current_shop_domain'),
            'user_id' => Auth::user()->id,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Product Added',
            'shopify_product_id' => $product->id
        ]);

        return redirect(route('products.index'))->with('success', 'Product Added Sucessfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.product-detail')->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.create')->with('prod', $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);


        $product->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);


        if($request->hasFile('images')) {
            foreach ($request->images as $image)
            {
                $image_url = $image->store('products');

                ProdImage::create([
                    'image' => $image_url,
                    'product_id' => $product->id
                ]);
            }
        }

        if($request->has('link')) {
            foreach ($request->link as $lin)
            {
                if(!(is_null($lin))) {
                    if(ProductLink::where('link', $lin)->exists()) {
                        $link = ProductLink::where('link', $lin)->first();
                    }
                    else {
                        $link = new ProductLink();
                    }
                    $link->link = $lin;
                    $link->product_id = $product->id;
                    $link->save();
                }
            }
        }

        if($request->has('vendor_name')) {
            $product_price_array = [];
            $product_link_array = [];
            $vendor_name_array = [];
            $moq_array = [];
            $lead_time_array = [];
            $weight_array = [];
            $length_array = [];
            $width_array = [];
            $height_array = [];
            $volume_array = [];




            $product_price_array = array_merge($product_price_array, $request->product_price);
            $product_link_array = array_merge($product_link_array, $request->product_link);
            $vendor_name_array = array_merge($vendor_name_array, $request->vendor_name);
            $moq_array = array_merge($moq_array, $request->moq);
            $lead_time_array = array_merge($lead_time_array, $request->leads_time);
            $weight_array = array_merge($weight_array, $request->weight);
            $length_array = array_merge($length_array, $request->length);
            $width_array = array_merge($width_array, $request->width);
            $height_array = array_merge($height_array, $request->height);


            for($i =0; $i< count($vendor_name_array); $i++) {

                if(!(is_null($vendor_name_array[$i]))) {
                    ProductVendorDetail::create([
                        'shopify_product_id' => $product->id,
                        'name' =>  $vendor_name_array[$i],
                        'cost' => $product_price_array[$i],
                        'url' => $product_link_array[$i],
                        'moq' => $moq_array[$i],
                        'leads_time' => $lead_time_array[$i],
                        'weight' => $weight_array[$i],
                        'length' => $length_array[$i],
                        'width' => $width_array[$i],
                        'height' => $height_array[$i],
                        'volume' => ($length_array[$i] * $width_array[$i] * $height_array[$i])
                    ]);
                }

            }


            Log::create([
                'user_id' => Auth::user()->id,
                'attempt_time' => Carbon::now()->toDateTimeString(),
                'attempt_location_ip' => $request->getClientIp(),
                'type' => 'Product Vendor Added',
                'shopify_product_id' => $product->id
            ]);

        }


        Log::create([
            'user_id' => Auth::user()->id,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Product Updated',
            'shopify_product_id' => $product->id
        ]);

        return redirect(route('products.index'))->with('success', 'Product Updated Sucessfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {

        foreach ($product->prod_images()->get() as $image) {
            Storage::delete($image->image);
        }
        $product->delete();


        Log::create([
            'user_id' => Auth::user()->id,
            'attempt_time' => Carbon::now()->toDateTimeString(),
            'attempt_location_ip' => $request->getClientIp(),
            'type' => 'Product Deleted',
            'shopify_product_id' => $product->id
        ]);

        return redirect()->back()->with('success', 'Product Deleted successfully!');
    }

    public function addVariantImages(Request $request, $id) {

        $this->validate($request, [
           'var_img' => 'required'
        ]);

        $variant = Variant::find($request->var_id);
        $image_url = $request->var_img[0]->store('variants');

        $variant->image = $image_url;
        $variant->save();

        return redirect()->back();
    }

    public function deleteProductImage($id) {
        $image = ProdImage::find($id);
        Storage::delete($image->image);
        $image->delete();

        return response()->json(['data' => 'success']);
    }


    public function deleteVariantImage($id) {
        $variant = Variant::find($id);

        Storage::delete($variant->image);
        $variant->image = null;
        $variant->save();

        return response()->json(['data' => 'success']);
    }

    public function deleteProductVariant($id) {
        $variant = Variant::find($id);


        if($variant->image) {
            Storage::delete($variant->image);
        }

        $variant->delete();

        return response()->json(['data' => 'success']);

    }

    public function updateProductVariant($id) {
        $variant = Variant::find($id);

        return view('products.variant-update')->with('variant', $variant);
    }

    public function updateVariant(Request $request, $id) {


        $variant = Variant::find($id);

        if(isset($request->images)) {
            $image_url = $request->images[0]->store('variants');
            Storage::delete($variant->image);
            $variant->image = $image_url;
        }

        $variant->variant_title = $request->variant_title;
        $variant->variant_price = $request->variant_price;
        $variant->variant_qty = $request->variant_qty;
        $variant->variant_sku = $request->variant_sku;
        $variant->barcode = $request->barcode;
        $variant->save();

        return redirect()->back()->with('success', 'Variant Updated successfully!');

    }


}
