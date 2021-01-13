<?php

namespace App\Http\Controllers;

use App\Shop;
use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyProduct;
use App\WordpressOrder;
use App\WordpressProduct;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_shop_type = Shop::where('id', session()->get('current_shop_domain'))->pluck('shop_type')->first();

        if($current_shop_type == 'shopify') {
            $products = ShopifyProduct::where('shop_id', session()->get('current_shop_domain'))->count();
            $customers = ShopifyCustomer::count();
            $orders = ShopifyOrder::where('shop_id', session()->get('current_shop_domain'))->count();
            return view('dashboard')->with([
                'products' => $products,
                'orders' => $orders,
                'customers' => $customers,
            ]);
        }elseif ($current_shop_type == 'wordpress'){

            $products = WordpressProduct::where('shop_id', session()->get('current_shop_domain'))->count();
            $customers = ShopifyCustomer::count();
            $orders = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->count();
            return view('dashboard')->with([
                'products' => $products,
                'orders' => $orders,
                'customers' => $customers,
            ]);

        }

    }
}
