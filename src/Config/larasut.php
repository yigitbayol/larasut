<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Parasut Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID given by Parasut
    |
    */
    'client_id' => env('LARASUT_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret given by Parasut
    |
    */
    'client_secret' => env('LARASUT_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Redirect URI
    |--------------------------------------------------------------------------
    |
    | Redirect URI given by Parasut
    |
    */
    'redirect_uri' => env('LARAVEL_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Company ID
    |--------------------------------------------------------------------------
    |
    | Company ID given by Parasut
    |
    */
    'company_id' => env('LARASUT_COMPANY_ID'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Username
    |--------------------------------------------------------------------------
    |
    | Username of Parasut App
    |
    */
    'username' => env('LARASUT_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Password
    |--------------------------------------------------------------------------
    |
    | Password of given User of Parasut App
    |
    */
    'password' => env('LARASUT_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Parasut API V4 URL
    |--------------------------------------------------------------------------
    |
    | API V4 URL provided by Parasut
    |
    */
    'api_v4_url' => env('LARASUT_API_V4_URL', 'https://api.parasut.com/v4/'),

    /*
    |--------------------------------------------------------------------------
    | Parasut API V4 URL
    |--------------------------------------------------------------------------
    |
    | API V4 URL provided by Parasut
    |
    */
    'api_url' => env('LARASUT_API_URL', 'https://api.parasut.com/'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Username
    |--------------------------------------------------------------------------
    |
    | Parasut Username
    |
    */
    'api_auth_url' => env('LARATUS_AUTH_URL', 'https://api.parasut.com/oauth/authorize'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Password
    |--------------------------------------------------------------------------
    |
    | Parasut Password
    |
    */
    'api_token_url' => env('LARASUT_TOKEN_URL', 'https://api.parasut.com/oauth/token'),


    /*
    |--------------------------------------------------------------------------
    | Default Customer Category ID
    |--------------------------------------------------------------------------
    |
    | Default Customer Category ID in Parasut
    |
    */
    'default_customer_category_name' => 'Website',

    /*
    |--------------------------------------------------------------------------
    | Default Sale Invoice Category ID
    |--------------------------------------------------------------------------
    |
    | Default Sale Invoice Category ID in Parasut
    |
    */
    'default_sales_invoice_category_name' => 'Website',
];
