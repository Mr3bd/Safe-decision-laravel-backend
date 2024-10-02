<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | You can use this file to configure your CORS settings. Here are the
    | available options:
    |
    | - allowed_methods: The HTTP methods that are allowed for CORS requests.
    | - allowed_origins: The origins that are allowed to make requests.
    | - allowed_origins_patterns: Patterns to match against allowed origins.
    | - allowed_headers: The headers that are allowed for CORS requests.
    | - exposed_headers: The headers that are exposed to the browser.
    | - max_age: The maximum age for the preflight response.
    | - supports_credentials: Indicates whether to allow credentials in requests.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'], // Use ['GET', 'POST', 'PUT', 'DELETE'] for specific methods

    'allowed_origins' => ['*'], // Use specific domains like ['http://localhost:3000'] for production

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Use specific headers like ['Content-Type', 'Authorization'] for production

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
