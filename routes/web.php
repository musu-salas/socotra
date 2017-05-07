<?php

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

Auth::routes();
Route::get( 'logout', 'Auth\LoginController@logout');

// Static pages routes.
Route::get( '/', 'LandingController@index');
Route::get( 'privacy-policy', 'LandingController@privacy');

// Facebook popup authentication route.
Route::get( 'socialize/facebook', 'Auth\FacebookController@socialize');

// Group public routes.
Route::get( 'classes/{group}', 'GroupController@index');
Route::get( 'classes/{group}/{locationId}', 'GroupController@show');
Route::post('classes/{group}/{locationId}', 'GroupController@show');

// Authentication protected routes
// with `/home` url prefix.
Route::group([
    'middleware' => ['auth', 'auth.custom'],
    'prefix' => 'home',
    'namespace' => 'Home'

], function() {
    Route::get( '/', 'IndexController@index');

    Route::get( 'settings', 'SettingsController@index');
    Route::post('settings', 'SettingsController@store');

    Route::get( 'classes', 'GroupController@index');
    Route::get( 'classes/new', 'GroupController@create');
    Route::post('classes/new', 'GroupController@store');

    // Authenticated user groups routes
    // with `/home/classes/{group}` url prefix.
    Route::group([
        'middleware' => 'group.my',
        'prefix' => 'classes/{group}',
        'namespace' => 'Group'

    ], function() {
        Route::get( '/', 'IndexController@index');

        Route::get( 'pricing', 'PricingController@index');
        Route::get( 'pricing/{price}', 'PricingController@show');
        Route::post('pricing/{price}', 'PricingController@store');

        Route::get( 'location', 'LocationController@index');
        Route::get( 'location/{locationId}', 'LocationController@show');
        Route::post('location/{locationId}', 'LocationController@store');
        Route::get( 'location/{locationId}/map', 'LocationController@map');
        Route::post('location/{locationId}/map', 'LocationController@map');

        Route::get( 'schedule/{locationId?}', 'ScheduleController@index');
        Route::get( 'schedule/{locationId}/{scheduleId}', 'ScheduleController@show');
        Route::post('schedule/{locationId}/{scheduleId}', 'ScheduleController@store');

        Route::get( 'overview', 'OverviewController@index');
        Route::post('overview', 'OverviewController@store');

        Route::get( 'contact', 'ContactController@index');
        Route::post('contact', 'ContactController@store');

        Route::get( 'photos', 'PhotosController@index');
    });
});

// API routes using session authentication (not yet a separate API oAuth2 Laravel offers)
// with `/api/v1` url prefix.
Route::group([
    'namespace' => 'Api',
    'prefix' => 'api/v1/'

], function() {
    Route::get(    '/', 'IndexController@index');
    // TODO: Remove pioneer related functionality, models, db tables.
    // Route::post(   'pioneer', 'PioneerController@store');

    // API routes for user operations
    // with `api/v1/users/{userId}` url prefix.
    Route::group([
        'namespace' => 'User',
        'prefix' => 'users/{user}',
        'middleware' => 'api.user'

    ], function() {
        Route::post(  'avatar', 'AvatarController@store');
        Route::put(   'avatar', 'AvatarController@crop');
        Route::delete('avatar', 'AvatarController@destroy');
    });

    // API routes for group manipulations
    // with `api/v1/classes/{group}` url prefix.
    Route::group([
        'namespace' => 'Group',
        'prefix' => 'classes/{group}'

    ], function() {
        Route::get(   'photos', 'PhotosController@index');
        Route::get(   'location/{location}/map', 'LocationController@map');
        Route::post(  'contact', 'ContactController@send');

        Route::group([
            'middleware' => 'api.owner'

        ], function() {
            Route::get(   'menu', 'MenuController@index');
            Route::delete('schedule/{schedule}', 'ScheduleController@destroy');
            Route::delete('location/{location}', 'LocationController@destroy');
            Route::delete('pricing/{pricing}', 'PricingController@destroy');
            Route::put(   'cover_photo', 'PhotosController@cover');
            Route::delete('photos/{photo}', 'PhotosController@destroy');
            Route::post(  'photos', 'PhotosController@store');
        });
    });
});