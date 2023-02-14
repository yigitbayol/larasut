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
    'client_id' => env('LARASUT_CLIENT_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Parasut Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret given by Parasut
    |
    */
    'client_secret' => env('LARASUT_CLIENT_SECRET', null),

    /*
    |--------------------------------------------------------------------------
    | Parasut Redirect URI
    |--------------------------------------------------------------------------
    |
    | Redirect URI given by Parasut
    |
    */
    'redirect_uri' => env('LARAVEL_REDIRECT_URI', 'urn:ietf:wg:oauth:2.0:oob'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Company ID
    |--------------------------------------------------------------------------
    |
    | Company ID given by Parasut
    |
    */
    'company_id' => env('LARASUT_COMPANY_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Parasut API V4 URL
    |--------------------------------------------------------------------------
    |
    | API V4 URL provided by Parasut
    |
    */
    'api_v4_url' => env('LARASUT_API_V4_URL', 'https://api.parasut.com/v4'),

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
    'api_v4_url' => env('LARASUT_API_V4_URL', 'https://api.parasut.com/v4'),

    /*
    |--------------------------------------------------------------------------
    | Parasut Password
    |--------------------------------------------------------------------------
    |
    | Parasut Password
    |
    */
    'api_url' => env('LARASUT_API_URL', 'https://api.parasut.com/'),
];
