<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'ses' => [
		'key' => env('AWS_KEY'),
		'secret' => env('AWS_SECRET'),
		'region' => env('SES_REGION'),
	],

	'facebook' => [
		'client_id' => env('FACEBOOK_CLIENT'),
		'client_secret' => env('FACEBOOK_SECRET'),
		'redirect' => env('FACEBOOK_REDIRECT'),
        'scopes' => ['public_profile', 'email', 'user_friends', 'user_hometown', 'user_website'],
        'empty_email' => 'EMPTY_FACEBOOK_EMAIL'
	],

];
