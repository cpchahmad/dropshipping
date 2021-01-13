<?php

namespace App\Http\Controllers;

use App\Shop;
use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyProduct;
use App\WordpressProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class ShopsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop_data = Shop::all();
//dd(session()->all());
        $products = ShopifyProduct::count();
//        $customers = ShopifyCustomer::count();
//        $orders = ShopifyOrder::count();
        if(!session()->has('current_shop_domain')){
            session()->put('current_shop_domain', $shop_data->first()->id);
        }
//dd($shop);
        return view('shops.view_all_shops', compact( 'shop_data', 'shopify_products_count', 'shopify_customers_count', 'shopify_orders_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::find(1);
        if(!$shop) {
            $shop = new Shop();
        }

        return view('shops.create')->with('shop',$shop);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->shop_type == "shopify") {
//            dd($request->shop_type);
            $this->validate($request, [
                'api_key' => 'required',
                'api_password' => 'required',
                'api_secret' => 'required',
                'shop_domain' => 'required|unique:shops',
                'shop_type' => 'required',
                'api_version' => 'required',
            ]);

            Shop::create([
                'user_id' => Auth::user()->id,
                'api_key' => $request->api_key,
                'api_password' => $request->api_password,
                'api_secret' => $request->api_secret,
                'shop_domain' => $request->shop_domain,
                'shop_type' => $request->shop_type,
                'api_version' => $request->api_version,
            ]);

            return redirect()->back()->with('success', 'Shope Connected Successfully!');
        }
        if($request->shop_type == "wordpress") {
            $this->validate($request, [
                'api_key' => 'required',
                'api_secret' => 'required',
                'shop_domain' => 'required|unique:shops',
                'shop_type' => 'required',
            ]);

            $woocommerce = new Client($request->shop_domain, $request->api_key, $request->api_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

            try {
                $products = $woocommerce->get('products');
            }
            catch(HttpClientException $e) {
                $error_msg = $e->getMessage(); // Error message.
                $e->getRequest(); // Last request data.
                $e->getResponse(); // Last response data
                return redirect()->back()->with('error', 'Your Credentials are incorrect. Please Try again!, Error:'.$error_msg);
            }

            $shop = new Shop();
            $shop->user_id = Auth::user()->id;
            $shop->shop_domain = $request->shop_domain;
            $shop->api_key = $request->api_key;
            $shop->api_secret = $request->api_secret;
            $shop->shop_type = $request->shop_type;
            $shop->api_version = 'wc/v3';
            $shop->api_password = 'null';
            $shop->save();
//            Auth::user()->has_woocommerce_shops()->attach([$shop->id]);
            return redirect()->back()->with('success', 'Shop Connected Successfully!');
        }

        return redirect()->back()->with('error', 'Select Shop Type!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function update_shop(Request $request, $id){
        $shop = Shop::find($id);
        $shop->api_key = $request->api_key;
        $shop->api_password = $request->api_password;
        $shop->api_secret = $request->api_secret;
        $shop->api_version = $request->api_version;
        if($shop->update()){
            return redirect()->back()->with('success', 'Shop Update Successfully!');
        }else{
            return redirect()->back()->with('error', 'Shop Not Updated');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Shop::find($id);
//        dd($shop);
        $shop->delete();
        dd("delete !");
//
    }

    public static function config($id) {

        $shop = ShopsController::getShop($id);

        // Create options for the API
        $options = new Options();
        $options->setType(true); // Makes it private
        $options->setVersion($shop->api_version);
        $options->setApiKey($shop->api_key);
        $options->setApiSecret($shop->api_secret);
        $options->setApiPassword($shop->api_password);


        // Create the client and session
        $api = new BasicShopifyAPI($options);

        $api->setSession(new Session($shop->shop_domain));

        return $api;

    }


    public static function getShop($id) {
        $shop = Shop::find($id);
//        dd($shop);
        return $shop;
    }


}
