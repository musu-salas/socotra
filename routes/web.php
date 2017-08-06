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

$subdomainScheme = '{country}.' . config('app.domain');
$locales = array_merge(config('locales'), ['']);

foreach($locales as $locale) {
    Route::group([
        'domain' => $subdomainScheme,
        'prefix' => $locale,
        'middleware' => ['url.country', 'url.locale']
    ], function () {

        Auth::routes();

        // TODO: Remove in favour of POST /logout route exposed by `Auth::routes()`:
        Route::get( 'logout', 'Auth\LoginController@logout');

        // Landing page.
        Route::get( '/', 'LandingController@index');

        // Privacy policy page.
        Route::get( 'privacy-policy', 'LandingController@privacy');

        // Facebook popup authentication.
        Route::get( 'socialize/facebook', 'Auth\FacebookController@socialize');

        // TODO: Forward `classes/{group}`, `classes/{group}/{location}` old routes to new multi-parameter routes.
        Route::get( 'classes/{group}/{location?}', 'GroupController@oldToNewForwarder');
        Route::get( 'classes/{city}/{district}/{discipline}/{title}/{location}', 'GroupController@show')->name('classpage.long');
        Route::get( 'classes/{city}/{discipline}/{title}/{location}', 'GroupController@show')->name('classpage.short');

        // Authentication protected routes
        // with `/home` url prefix.
        Route::group([
            'middleware' => ['auth', 'auth.custom'],
            'namespace' => 'Home'
        ], function() {

            Route::get( 'home', 'IndexController@index');

            Route::get( 'home/settings', 'SettingsController@index');
            Route::post('home/settings', 'SettingsController@store');

            Route::get( 'home/classes', 'GroupController@index');
            Route::get( 'home/classes/new', 'GroupController@create');
            Route::post('home/classes/new', 'GroupController@store');

            // Authenticated user groups routes
            // with `/home/classes/{group}` url prefix.
            Route::group([
                'middleware' => 'group.my',
                'namespace' => 'Group'
            ], function() {

                Route::get( 'home/classes/{group}', 'IndexController@index');

                Route::get( 'home/classes/{group}/pricing', 'PricingController@index');
                Route::get( 'home/classes/{group}/pricing/{price}', 'PricingController@show');
                Route::post('home/classes/{group}/pricing/{price}', 'PricingController@store');

                Route::get( 'home/classes/{group}/location', 'LocationController@index');
                Route::get( 'home/classes/{group}/location/{locationId}', 'LocationController@show');
                Route::post('home/classes/{group}/location/{locationId}', 'LocationController@store');
                Route::get( 'home/classes/{group}/location/{locationId}/map', 'LocationController@map');
                Route::post('home/classes/{group}/location/{locationId}/map', 'LocationController@map');

                Route::get( 'home/classes/{group}/schedule/{locationId?}', 'ScheduleController@index');
                Route::get( 'home/classes/{group}/schedule/{locationId}/{scheduleId}', 'ScheduleController@show');
                Route::post('home/classes/{group}/schedule/{locationId}/{scheduleId}', 'ScheduleController@store');

                Route::get( 'home/classes/{group}/overview', 'OverviewController@index');
                Route::post('home/classes/{group}/overview', 'OverviewController@store');

                Route::get( 'home/classes/{group}/contact', 'ContactController@index');
                Route::post('home/classes/{group}/contact', 'ContactController@store');

                Route::get( 'home/classes/{group}/photos', 'PhotosController@index');
            });
        });
    });
}

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