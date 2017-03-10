<?php

return [
    'class' => [
        'max_photos' => 12,
        'max_photosize' => 10 * 1024 * 1024, // in bytes
    ],

    'aws' => [

        /*
        |-----------------------------------------------------------------------
        | AWS S3 - Bucket URI
        |-----------------------------------------------------------------------
        | Supported: A full url with a slash in the end.
        */

        's3_bucket_link' => 'https://s3.' . env('S3_REGION') . '.amazonaws.com/' . env('S3_BUCKET') . '/',

        'user' => [
            'avatar' => [
                'path' => 'user_avatars/',
                'max_width' => 800,
                'max_height' => 600,
                'extension' => 'jpg'
            ]
        ],

        'class' => [

            /*
            |-------------------------------------------------------------------
            | AWS S3 - Class Photos Path
            |-------------------------------------------------------------------
            | Supported: Paths start at root / and do not need starting slash.
            */
            'photos_path' => 'class_public_photos/',

            /*
            |-------------------------------------------------------------------
            | AWS S3 - Class Photos Values
            |-------------------------------------------------------------------
            | This is the maximum image width/height allowed by our system for
            | photos uploaded by users. It is used to prevent overuse and to
            | keep file sizes in reasonable limits for faster loading.
            */

            'photos' => [

                // The keys 'original', 'large' & 'thumnail' must match the
                // table columns or else you crashed.

                'original' => [
                    'key' => '_o',
                    'maxw' => 5760,
                    'maxh' => 3840
                ],

                'large' => [
                    'key' => '_l',
                    'maxw' => 1440,
                    'maxh' => 960
                ],

                'thumbnail' => [
                    'key' => '_t',
                    'maxw' => 450,
                    'maxh' => 300
                ]
            ],

        ],

    ],

];
