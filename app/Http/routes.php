<?php

Route::group(['middleware' => ['web']], function () {
    Route::auth();

    //
    Route::get( '/',                                            'LandingController@index');
    Route::get( 'privacy-policy',                               'LandingController@privacy');

    //socialize/facebook
    Route::get( 'socialize/facebook',                           'Auth\FacebookController@socialize');

    //classes
    Route::get( 'classes/{groupId}',                            'GroupController@index');
    Route::get( 'classes/{groupId}/{locationId}',               'GroupController@show');
    Route::post('classes/{groupId}/{locationId}',               'GroupController@show');

    //home
    Route::group([
        'middleware' => 'auth',
        'prefix' => 'home',
        'namespace' => 'Home'

    ], function() {
        Route::get( '/',                                        'IndexController@index');

        Route::get( 'settings',                                 'SettingsController@index');
        Route::post('settings',                                 'SettingsController@store');

        Route::get( 'classes',                                  'GroupController@index');
        Route::get( 'classes/new',                              'GroupController@create');
        Route::post('classes/new',                              'GroupController@store');

        //classes/{group}
        Route::group([
            'middleware' => 'mygroup',
            'prefix' => 'classes/{group}',
            'namespace' => 'Group'

        ], function() {
            Route::get( '/',                                    'IndexController@index');

            Route::get( 'pricing',                              'PricingController@index');
            Route::get( 'pricing/{price}',                      'PricingController@show');
            Route::post('pricing/{price}',                      'PricingController@store');

            Route::get( 'location',                             'LocationController@index');
            Route::get( 'location/{locationId}',                'LocationController@show');
            Route::post('location/{locationId}',                'LocationController@store');
            Route::get( 'location/{locationId}/map',            'LocationController@map');
            Route::post('location/{locationId}/map',            'LocationController@map');

            Route::get( 'schedule/{locationId?}',               'ScheduleController@index');
            Route::get( 'schedule/{locationId}/{scheduleId}',   'ScheduleController@show');
            Route::post('schedule/{locationId}/{scheduleId}',   'ScheduleController@store');

            Route::get( 'overview',                             'OverviewController@index');
            Route::post('overview',                             'OverviewController@store');

            Route::get( 'contact',                              'ContactController@index');
            Route::post('contact',                              'ContactController@store');

            Route::get( 'photos',                               'PhotosController@index');
            Route::post('photos',                               'PhotosController@store');
        });
    });

    //api/v1
    Route::group([
        'namespace' => 'Api',
        'prefix' => 'api/v1/'

    ], function() {
        Route::get( '/',                                        'IndexController@index');
        Route::post('pioneer',                                  'PioneerController@store');

        //api/v1/users/{userId}
        Route::group([
            'namespace' => 'User',
            'prefix' => 'users/{userId}',
            'middleware' => 'user'

        ], function() {
            Route::group([
                'middleware' => 'api.user'

            ], function() {
                Route::post(  'avatar',                         'AvatarController@store');
                Route::put(   'avatar',                         'AvatarController@crop');
                Route::delete('avatar',                         'AvatarController@destroy');
            });
        });

        //api/v1/classes/{group}
        Route::group([
            'namespace' => 'Group',
            'prefix' => 'classes/{group}',
            'middleware' => 'group'

        ], function() {
            Route::get(  'photos',                              'PhotosController@index');
            Route::get(  'location/{locationId}/map',           'LocationController@map');
            Route::post( 'contact',                             'ContactController@send');

            Route::group([
                'middleware' => 'api.owner'

            ], function() {
                Route::get(   'menu',                           'MenuController@index');

                Route::delete('schedule/{scheduleId}',          'ScheduleController@destroy');

                Route::delete('location/{locationId}',          'LocationController@destroy');

                Route::delete('pricing/{pricingId}',            'PricingController@destroy');

                Route::put(   'cover_photo',                    'PhotosController@cover');
                Route::delete('photos/{photoId}',               'PhotosController@destroy');
            });
        });
    });
});