<?php

return [

/*
|--------------------------------------------------------------------------
| Cross-Origin Resource Sharing (CORS) Configuration
|--------------------------------------------------------------------------
|
| Here you may configure your settings for cross-origin resource sharing
| or "CORS". This determines what cross-origin operations may execute
| in web browsers. You are free to adjust these settings as needed.
|
*/

'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

'allowed_methods' => ['*'],

// Aquí va el dominio o dominios de tu frontend Angular
'allowed_origins' => [
    'http://localhost:4200',
    'http://localhost',
    'http://seatlookfor.duckdns.org',
    'https://seatlookfor.duckdns.org',
    'http://34.205.74.0',
    'http://34.205.74.0:80',
    'http://34.205.74.0:4200'
],

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

// IMPORTANTE para que las cookies se envíen en peticiones cross-origin
'supports_credentials' => true,

];
