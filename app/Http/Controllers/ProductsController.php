<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProdImage;
use App\SubCategory;
use App\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('products.index')->with('products', Product::where('outsource_id', auth()->user()->id)->simplePaginate(20));
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

        if(is_null($request->tags)) {
           $tags = null;
        }
        else {
            $tags = implode(", ", $request->tags);
        }


        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
            'compare_price' => 'required',
        ]);


        $product = Product::create([
           'title' => $request->title,
           'description' => $request->description,
           'price' => $request->price,
           'compare_price' => $request->compare_price,
           'weight' => $request->weight,
           'quantity' => $request->quantity,
           'tags' => $tags,
           'outsource_id' => auth()->user()->id,
           'unit' => $request->unit
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

        if($request->varient_check == 'on') {

            for($i=0; $i< count($request->var_title); $i++) {

                $variant = new Variant();

                if(isset($request->key1)) {
                    $variant->option1 = json_encode($request->value1);
                }

                if(isset($request->key2)) {
                    $variant->option2 = json_encode($request->value2);
                }

                if(isset($request->key3)) {
                    $variant->option3 = json_encode($request->value3);
                }

                $variant->product_id = $product->id;
                $variant->variant_title = $request->var_title[$i];
                $variant->variant_price = $request->var_price[$i];
                $variant->variant_qty = $request->var_qty[$i];
                $variant->variant_sku = $request->var_sku[$i];
                $variant->barcode = $request->var_barcode[$i];
                $variant->save();

            }

        }

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

        if(is_null($request->tags)) {
            $tags = null;
        }
        else {
            $tags = implode(", ", $request->tags);
        }

        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
            'compare_price' => 'required',
        ]);


        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'tags' => $tags,
            'outsource_id' => auth()->user()->id,
            'unit' => $request->unit
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

        if($request->var_id) {
            for($i=0; $i< count($request->var_id); $i++) {

                $variant = Variant::find($request->var_id[$i]);

                $variant->update([
                    'product_id' => $product->id,
                    'variant_title' => $request->var_title[$i],
                    'variant_price' => $request->var_price[$i],
                    'variant_qty' => $request->var_qty[$i],
                    'variant_sku' => $request->var_sku[$i],
                    'barcode' => $request->var_barcode[$i],
                ]);
            }
        }

        return redirect(route('products.index'))->with('success', 'Product Updated Sucessfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        foreach ($product->prod_images()->get() as $image) {
            Storage::delete($image->image);
        }
        $product->delete();

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
