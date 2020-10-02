<?php

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

// Resource Routes
Route::resource('shops', 'ShopsController');
Route::resource('products', 'ProductsController');
Route::post('/product/{id}/add/variant/images', 'ProductsController@addVariantImages')->name('add.images');
Route::get('/delete/product/image/{id}', 'ProductsController@deleteProductImage')->name('delete.product.image');
Route::get('/delete/variant/image/{id}', 'ProductsController@deleteVariantImage')->name('delete.variant.image');
Route::get('/delete/variant/{id}', 'ProductsController@deleteProductVariant')->name('delete.product.variant');
Route::get('/product/variant/update/{id}', 'ProductsController@updateProductVariant')->name('update.product.variant');
Route::put('/product/variant/edit/{id}', 'ProductsController@updateVariant')->name('edit.product.variant');
Route::resource('vendors', 'VendorsController');

// Admin Routes
Route::get('/admin/customers', 'AdminController@getCustomers')->name('admin.customers');
Route::get('/admin/orders', 'AdminController@getOrders')->name('admin.orders');
Route::get('/admin/products', 'AdminController@getProducts')->name('admin.products');
Route::get('/admin/products/reports', 'AdminController@getReports')->name('admin.products.reports');
Route::get('/admin/users/logs', 'AdminController@getLogs')->name('admin.logs');
Route::get('/admin/outsource/products', 'AdminController@getOutsourceProducts')->name('admin.outsource.products');
Route::post('/admin/approve/products/{id}', 'AdminController@approveProduct')->name('admin.approve.products');
Route::post('/admin/reject/products/{id}', 'AdminController@rejectProduct')->name('admin.reject.products');
Route::get('/admin/products/{id}', 'AdminController@showProductDetails')->name('admin.products.details');
Route::get('/admin/orders/{id}', 'AdminController@showOrderDetails')->name('admin.orders.details');
Route::post('/admin/add/vendor/{id}', 'AdminController@addVendorForProduct')->name('admin.add.product.vendor');
Route::get('/admin/users', 'AdminController@getUsers')->name('admin.users');
Route::post('/admin/store/user', 'AdminController@storeUser')->name('admin.store.user');
Route::get('/admin/show/user/{id}', 'AdminController@showUser')->name('admin.show.user');
Route::delete('/admin/delete/user/{id}', 'AdminController@deleteUser')->name('admin.delete.user');
Route::put('/admin/edit/user/{id}', 'AdminController@editUser')->name('admin.edit.user');
Route::post('/admin/change/order/status/{id}', 'AdminController@changeOrderStatus')->name('admin.change.order.status');
Route::post('/admin/add/order/tracking/{id}', 'AdminController@addTracking')->name('admin.add.tracking');
Route::post('/admin/fulfill/orders', 'AdminController@fulfillOrders')->name('admin.fulfill.orders');
Route::post('/admin/store/order/notes/{id}', 'AdminController@storeOrderNotes')->name('admin.store.order.notes');
Route::post('/admin/store/order/vendor', 'AdminController@storeOrderVendor')->name('admin.store.order.vendor');
Route::post('/orders/bulk-fulfillments', 'AdminController@showBulkFulfillments')->name('app.orders.bulk.fulfillment');


Route::get('/store/orders', 'AdminController@storeOrders');
Route::get('/store/products', 'AdminController@storeProducts');
Route::get('/store/customers', 'AdminController@storeCustomers');

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('/config', 'ShopsController@config');


Route::get('/dummy', function () {
    return view('products.new');
});

// Webhooks
Route::get('create/webhooks', 'AdminController@createWebhooks');
Route::get('get/webhooks', 'AdminController@getWebhooks');

Route::post('webhook/product/create', 'AdminController@productCreateWebhook');
Route::post('webhook/product/delete', 'AdminController@productDeleteWebhook');
Route::post('webhook/order/create', 'AdminController@orderCreateWebhook');
Route::post('webhook/order/fulfilled', 'AdminController@orderFulfilledWebhook');


