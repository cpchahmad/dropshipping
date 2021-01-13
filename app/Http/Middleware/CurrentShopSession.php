<?php

namespace App\Http\Middleware;

use App\Shop;
use Closure;

class CurrentShopSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shop_data = Shop::all();
        if(!session()->has('current_shop_domain')){
            session()->put('current_shop_domain', $shop_data->first()->id);
        }
        return $next($request);
    }
}
