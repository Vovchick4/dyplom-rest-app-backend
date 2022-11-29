<?php

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

Route::group(['namespace' => 'Api\Client', 'prefix' => 'client', 'as' => 'client.'], static function () {

    //Add new tables
    Route::post("tables", "TableController@store");

    Route::group(['prefix' => 'auth'], static function () {
        Route::middleware(['auth:client', 'scope:client'])->get('get-user', 'Auth\LoginController@getUser')->name('get-user');
        Route::post('login', 'Auth\LoginController@login')->name('login');
        Route::post('register', 'Auth\RegisterController@register')->name('register');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('reset.password');
        Route::middleware(['auth:client', 'scope:client'])->post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail')->name('confirm.email');
        // Login with social
        Route::get('login/{provider}/redirect', 'Auth\SocialController@redirectToProvider')->name('social.redirect');
        Route::get('login/{provider}', 'Auth\SocialController@login')->name('social.login');
    });

    // restaurants
    Route::get('restaurants/{restaurant:slug}', 'RestaurantController@show')->name('restaurants.show');
    // clients
    Route::middleware(['auth:client', 'scope:client', 'client.status'])->patch('clients/update', 'ClientController@update')->name('clients.update');
    Route::middleware(['auth:client', 'scope:client', 'client.status'])->resource('clients', 'ClientController')->only([
        'show',
    ]);
    // orders
    Route::middleware(['auth:client', 'scope:client', 'client.status'])->get('orders', 'OrderController@index')->name('orders.index');
    Route::middleware('order.quantity')->post('orders', 'OrderController@store')->name('orders.store');
    Route::resource('orders', 'OrderController')->only([
        'show'
    ]);
    Route::get('paypal-success', 'OrderController@paypalPaymentSuccess')->name('paypal.success');
    Route::get('paypal-cancel', 'OrderController@paypalPaymentCancel')->name('paypal.cancel');
    // categories
    Route::get('restaurants/{restaurant_id}/categories', 'CategoryController@index')
        ->where('restaurant_id', '[0-9]+')
        ->name('categories.index');
    Route::middleware('category.active')->resource('categories', 'CategoryController')->only([
        'show'
    ]);
    // plates
    Route::get('restaurants/{restaurant_id}/plates/search/{searchText}', 'PlateController@search')
        ->where('restaurant_id', '[0-9]+')
        ->name('plates.search');
    Route::middleware('plate.active')->resource('plates', 'PlateController')->only([
        'show'
    ]);
    Route::get('restaurants/{restaurant_id}/plates', 'PlateController@index')
        ->where('restaurant_id', '[0-9]+')
        ->name('plates.index');
});

Route::group(['namespace' => 'Api\Admin', 'prefix' => 'admin', 'as' => 'admin.'], static function () {

    Route::group(['prefix' => 'auth'], static function () {
        Route::middleware(['auth:user', 'scope:user'])->get('get-user', 'Auth\LoginController@getUser')->name('get-user');
        Route::post('login', 'Auth\LoginController@login')->name('login');
        Route::post('register', 'Auth\RegisterController@register')->name('register');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('reset.password');
        Route::middleware(['auth:user', 'scope:user'])->post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail')->name('confirm.email');
        // Login with social
        Route::get('login/{provider}/redirect', 'Auth\SocialController@redirectToProvider')->name('social.redirect');
        Route::get('login/{provider}', 'Auth\SocialController@login')->name('social.login');
    });

    Route::group(['middleware' => ['auth:user', 'scope:user', 'user.status']], static function () {
        // Tables
        Route::get('tables', 'TableController@index')->name("tables.index");
        // plates
        Route::group(['middleware' => ['plate.access']], static function () {
            Route::resource('plates', 'PlateController')->only(['show', 'update', 'destroy']);
        });
        Route::post('plates', 'PlateController@store')->name('plates.store');
        Route::get('plates', 'PlateController@index')->name('plates.index');
        // categories
        Route::group(['middleware' => ['category.access']], static function () {
            Route::resource('categories', 'CategoryController')->only(['show', 'update', 'destroy']);
            Route::get('/categories/{category}/plates-list', 'CategoryController@platesList')
                ->where('category', '[0-9]+')
                ->name('categories.plates-list');
            Route::post('/categories/{category}/plates-sync', 'CategoryController@platesSync')
                ->where('category', '[0-9]+')
                ->name('categories.plates-sync');
        });
        Route::post('categories', 'CategoryController@store')->name('categories.store');
        Route::get('categories', 'CategoryController@index')->name('categories.index');
        // orders
        Route::group(['middleware' => ['order.access']], static function () {
            Route::resource('orders', 'OrderController')->only(['show', 'update', 'destroy']);
        });
        Route::get('orders', 'OrderController@index')->name('orders.index');
        // users

        Route::middleware(['auth:user', 'scope:user', 'user.status'])->patch('users/update', 'UserController@update')->name('users.update');
        Route::resource('users', 'UserController')->only([
            'index', 'show', 'destroy'
        ]);

        // super admin
        Route::group(['middleware' => ['super-admin']], static function () {

            Route::resource('restaurants', 'RestaurantController')->only([
                'index', 'show', 'store', 'update', 'destroy'
            ]);

            Route::get('restaurants/search/{searchText}', 'RestaurantController@search')
                ->name('restaurants.search');
        });
    });
});
