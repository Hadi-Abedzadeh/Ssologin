<?php
return [

    /*
    |--------------------------------------------------------------------------
    | SSO URL
    |--------------------------------------------------------------------------
    */
    'url'      => '127.0.0.1:8000/service/v1/sso-auth',

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    // The prefix used in all base routes
    'route_prefix'              =>  '',
    // The prefix used in api endpoints
    'api_prefix'                => 'api',
    // The prefix used in admin route
    'admin_prefix'              => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    // route middleware
    'route_middleware'          =>  ['web'],
    // api middleware
    'api_middleware'            =>  ['api'],
    // admin middleware
    'admin_middleware'          =>  ['web'],
];
