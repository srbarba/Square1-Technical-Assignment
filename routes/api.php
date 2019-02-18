<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// In real case this will be an unique ID. Meantime this require the slug and id from origin website.
Route::get('/simulate/catalog/category/{id}/{page?}', 'Api\Simulate\ApiSimulateCategory@index');
Route::get('/simulate/catalog/product/{slug}/{id}', 'Api\Simulate\ApiSimulateProduct@index');

// Auth Controllers
Route::post('auth/register', 'Api\Auth\RegisterController@index');
Route::post('auth/login', 'Api\Auth\LoginController@index');

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'Api\Auth\UserController@index');
    Route::post('auth/logout', 'Api\Auth\LogoutController@index');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'Api\Auth\RefreshController@index');
});

// Catalog Controllers
Route::get('catalog/categories/{id}', 'Api\Catalog\CategoryController@getById')->where('id', '[0-9]+');
Route::get('catalog/categories/{slug}', 'Api\Catalog\CategoryController@getBySlug')->where('slug', '.*');
Route::get('catalog/categories', 'Api\Catalog\CategoryController@getAll');

Route::get('catalog/products/user/{id}', 'Api\Catalog\ProductController@getByUser')->where('id', '[0-9]+');
Route::get('catalog/products/{id}', 'Api\Catalog\ProductController@getById')->where('id', '[0-9]+');
Route::get('catalog/products/{slug}', 'Api\Catalog\ProductController@getBySlug')->where('slug', '.*');
Route::get('catalog/products/', 'Api\Catalog\ProductController@getAll');

Route::get('profile/wishlist/add/{user_id}/{product_id}', 'Api\Profile\WishlistController@addProduct');
Route::get('profile/wishlist/remove/{user_id}/{product_id}', 'Api\Profile\WishlistController@removeProduct');
