<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|----------------------------------------------------------
|  User API Routes
|----------------------------------------------------------
*/

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
Route::post('update-profile', 'UserController@update');
Route::post('update-pass', 'UserController@passUpdate');
Route::get('profile-details', 'UserController@details');
Route::post('logout', 'UserController@logout');
Route::post('upload-image', 'UserController@imageUpload');
});

Route::post('password/email', 'ForgotPasswordController@forgot');

Route::post('password/reset', 'ForgotPasswordController@reset');

/*
|----------------------------------------------------------
|  Product & Category API Routes
|----------------------------------------------------------
*/

//list products
 Route::get('products', 'ProductController@indexProduct');

//list categories
 Route::get('categories', 'CategoryController@index');

//Show single Category details
 Route::get('categories/{id}', 'CategoryController@show');


/*
|----------------------------------------------------------
|  Cart API Routes
|----------------------------------------------------------
*/

//List Cart Items
Route::get('carts', 'CartController@index');

//Cart count
Route::get('count-total', 'CartController@count_and_total');

//Add To Cart
Route::post('add-to-cart/{id}', 'CartController@add_to_cart');

//Remove From Cart
Route::delete('remove-from-cart/{id}', 'CartController@remove_from_cart');

//Increase Quantity
Route::post('incr-from-cart/{id}', 'CartController@incr_qty');

//Decrease Quantity
Route::post('decr-from-cart/{id}', 'CartController@decr_qty');


/*
|----------------------------------------------------------
|  Banner API Routes
|----------------------------------------------------------
*/

//Main Banners
Route::get('main-banners', 'BannerController@mainBanner');
//Offer Banners
Route::get('offer-banners', 'BannerController@offerBanner');


/*
|----------------------------------------------------------
|  Wishlist API Routes
|----------------------------------------------------------
*/

//Get Wishlist
Route::get('wishlist', 'FavController@index');
Route::post('wishlist/add/{id}', 'FavController@add');
Route::delete('wishlist/del/{id}', 'FavController@remove');


/*
|----------------------------------------------------------
|  Addresslist API Routes
|----------------------------------------------------------
*/

Route::get('address', 'AddressBookController@index');
Route::post('address/add', 'AddressBookController@store');
Route::post('address/update/{id}', 'AddressBookController@update');
Route::delete('address/del/{id}', 'AddressBookController@destroy');