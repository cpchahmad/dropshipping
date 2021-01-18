<?php

use App\Http\Controllers\ShopsController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => ['auth', 'currentShopSession']], function () {
    // Resource Routes
    Route::resource('shops', 'ShopsController');
    Route::post('shops/{id}', 'ShopsController@update_shop')->name('shops.update');
    Route::resource('products', 'ProductsController');

    //    Haseeb Routes

    Route::post('/shop_domain', 'AdminController@createShopSection')->name('shop');

//  Sync wordpress products
    Route::post('/sync-wordpress-products/{id}', 'WordpressController@sync_wordpress_product')->name('sync-wordpress-products');

//    Sync Wordpress Orders
    Route::post('/sync-wordpress-orders/{id}', 'WordpressController@sync_wordpress_order')->name('sync-wordpress-orders');

//    Haseeb Routes End

    Route::post('/product/{id}/add/variant/images', 'ProductsController@addVariantImages')->name('add.images');
    Route::get('/delete/product/image/{id}', 'ProductsController@deleteProductImage')->name('delete.product.image');
    Route::get('/delete/variant/image/{id}', 'ProductsController@deleteVariantImage')->name('delete.variant.image');
    Route::get('/delete/variant/{id}', 'ProductsController@deleteProductVariant')->name('delete.product.variant');
    Route::get('/product/variant/update/{id}', 'ProductsController@updateProductVariant')->name('update.product.variant');
    Route::put('/product/variant/edit/{id}', 'ProductsController@updateVariant')->name('edit.product.variant');
    Route::resource('expenses', 'ExpenseController');
    Route::resource('categories', 'CategoriesController');

    // Admin Routes
    Route::get('/admin/customers', 'AdminController@getCustomers')->name('admin.customers');
    Route::get('/admin/orders', 'AdminController@getOrders')->name('admin.orders');
    Route::get('/admin/products', 'AdminController@getShopifyProducts')->name('admin.products');
    Route::get('/admin/products', 'AdminController@get_products')->name('admin.products');
    Route::get('/all/products', 'AdminController@allProducts')->name('shopify.products');
    Route::get('/admin/products/reports', 'AdminController@getReports')->name('admin.products.reports');
    Route::get('/admin/users/logs', 'AdminController@getLogs')->name('admin.logs');
    Route::get('/admin/outsource/products', 'AdminController@getOutsourceProducts')->name('admin.outsource.products');
    Route::post('/admin/approve/products/{id}', 'AdminController@approveProduct')->name('admin.approve.products');
    Route::post('/admin/reject/products/{id}', 'AdminController@rejectProduct')->name('admin.reject.products');
    Route::get('/admin/products/{id}', 'AdminController@showProductDetails')->name('admin.products.details');
    Route::get('/admin/orders/{id}', 'AdminController@showOrderDetails')->name('admin.orders.details');
    Route::get('/admin/show/line/images/{id}', 'AdminController@showLineImages')->name('admin.show.line.images');
    Route::get('/admin/wordpress/show/line/images/{id}', 'AdminController@showWordpressLineImages')->name('admin.wordpress.show.line.images');
    Route::post('/admin/add/vendor/{id}', 'AdminController@addVendorForProduct')->name('admin.add.product.vendor');
    Route::delete('/admin/delete/vendor/{id}', 'AdminController@deleteVendorForProduct')->name('admin.delete.product.vendor');
    Route::put('/admin/edit/vendor/{id}', 'AdminController@editVendorForProduct')->name('admin.edit.product.vendor');
    Route::get('/admin/users', 'AdminController@getUsers')->name('admin.users');
    Route::post('/admin/store/user', 'AdminController@storeUser')->name('admin.store.user');
    Route::get('/admin/sync/orders/{id}', 'AdminController@adminSyncOrders')->name('admin.sync.order');
    Route::get('/admin/show/user/{id}', 'AdminController@showUser')->name('admin.show.user');
    Route::delete('/admin/delete/user/{id}', 'AdminController@deleteUser')->name('admin.delete.user');
    Route::put('/admin/edit/user/{id}', 'AdminController@editUser')->name('admin.edit.user');
    Route::post('/admin/change/order/status/{id}', 'AdminController@changeOrderStatus')->name('admin.change.order.status');
    Route::post('/admin/print/order/shipping/{id}', 'AdminController@printOrderShipping')->name('admin.print.order.shipping');
    Route::post('/admin/add/order/tracking', 'AdminController@addTracking')->name('admin.add.tracking');
    Route::post('/admin/fulfill/orders', 'AdminController@fulfillOrders')->name('admin.fulfill.orders');
    Route::post('/admin/store/order/notes/{id}', 'AdminController@storeOrderNotes')->name('admin.store.order.notes');
    Route::post('/admin/store/order/shipping/price/{id}', 'AdminController@addOrderShippingPrice')->name('admin.store.order.shipping.price');
    Route::post('/admin/store/order/vendor', 'AdminController@storeOrderVendor')->name('admin.store.order.vendor');
    Route::post('/orders/bulk-fulfillments', 'AdminController@showBulkFulfillments')->name('app.orders.bulk.fulfillment');

    // Data Syncing Routes
    Route::get('/store/orders', 'AdminController@storeOrders');
    Route::get('/store/products', 'AdminController@storeProducts')->name('sync.shopify.products');
    Route::get('/store/customers', 'AdminController@storeCustomers');

    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::get('/config', 'ShopsController@config');

});

//Webhooks
Route::get('create/webhooks', 'AdminController@createWebhooks');
Route::get('get/webhooks', 'AdminController@getWebhooks');//To register webhook on woocommerce
Route::get('delete/webhooks', 'AdminController@deleteWebhooks');

Route::post('webhook/products-create', 'AdminController@productCreateWebhook');
Route::post('webhook/products-update', 'AdminController@productUpdateWebhook');
Route::any('webhook/orders-create', 'AdminController@orderCreateWebhook');
Route::post('webhook/orders-update', 'AdminController@orderUpdatedWebhook');

//Route::post('/woocommerce/products-create', 'AdminController@productCreateWebhook');
//Route::post('/woocommerce/products-update', 'AdminController@productUpdateWebhook');
Route::any('/woocommerce/orders-create', 'AdminController@orderCreateWebhook');
Route::post('/woocommerce/orders-update', 'AdminController@orderUpdatedWebhook');


//
//
//Route::get('/test', function() {
//    $api = ShopsController::config();
//    $response = $api->rest('GET', '/admin/orders/count.json', null, [], true);
//    dd($response);
//});

            // Session Testing
//Route::get('profile', 'AdminController@profile')->name('profile');
//
//Route::get('/login', 'AdminController@login_view')->name('login');
//Route::post('set', 'AdminController@set_session')->name('set_session');
//Route::get('logout', function(){
//    session()->forget('data');
//    return redirect()->route('login');
//})->name('logout');

Route::get('test', function() {
   $orders = \App\ShopifyOrder::all();
   dd(count($orders));
});

