<?php

namespace App\Http\Controllers;

use App\Shop;
use Illuminate\Http\Request;
use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = new Shop();
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
        $this->validate($request, [
           'api_key' => 'required',
           'api_password' => 'required',
           'api_secret' => 'required',
           'shop_domain' => 'required',
           'api_version' => 'required',
        ]);



        Shop::create([
            'api_key' => $request->api_key,
            'api_password' => $request->api_password,
            'api_secret' => $request->api_secret,
            'shop_domain' => $request->shop_domain,
            'api_version' => $request->api_version,
        ]);

        return redirect()->back()->with('success', 'Shop setting created successfully!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function config() {

        $shop = ShopsController::getShop();

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


    public static function getShop() {
        $shop = Shop::find(1);
        return $shop;
    }


}
