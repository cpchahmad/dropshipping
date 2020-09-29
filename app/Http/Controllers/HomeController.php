<?php

namespace App\Http\Controllers;

use App\ShopifyCustomer;
use App\ShopifyOrder;
use App\ShopifyProduct;
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
        $products = ShopifyProduct::count();
        $customers = ShopifyCustomer::count();
        $orders = ShopifyOrder::count();
        return view('dashboard')->with([
            'products' => $products,
            'orders' => $orders,
            'customers' => $customers,
        ]);
    }
}
