<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DirectAdmin URL
    |--------------------------------------------------------------------------
    |
    | The full URL to your DirectAdmin installation including port number.
    | Default DirectAdmin SSL port is 2222
    |
    */
    'url' => env('DIRECTADMIN_URL', 'https://mail.jupitercorporateservices.com:2222'),

    /*
    |--------------------------------------------------------------------------
    | DirectAdmin Username
    |--------------------------------------------------------------------------
    |
    | Your DirectAdmin account username
    |
    */
    'username' => env('DIRECTADMIN_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | DirectAdmin Password
    |--------------------------------------------------------------------------
    |
    | Your DirectAdmin account password or login key
    |
    */
    'password' => env('DIRECTADMIN_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Domain Name
    |--------------------------------------------------------------------------
    |
    | The domain name for which to fetch email accounts
    |
    */
    'domain' => env('DIRECTADMIN_DOMAIN', 'shoreshotelng.com'),
];
