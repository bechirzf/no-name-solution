<?php

/**
 * Custom Application  configuration files.
 * 
 */

return [
    'jwt' =>[
        // Maximum time() and iat difference in seconds.
        'max_iat' => 10,

        // Default algorithm and token type.
        'alg' => 'HS256',
        'typ' => 'JWT'
    ],
    'cache' => [
        'expires_in' => '1440',
    ], 
    'cors' =>[
        'allowed_origins' => [
                'http://localhost',
                'http://localhost:80',
                'http://172.30.229.24',
                'http://172.30.229.24:3000',
                env('FRONTEND_APP_ORIGIN', 'http://localhost:3000')
            ],
        'allowed_ips' => ['http://172.30.229.24:3000','127.0.0.1','http://localhost:3000'],
        'allowed_domain' => ['corporate.ingrammicro.com'], 
    ], 
    // 'secret_key' => env('APP_KEY', '5h312iNc012pI_I7_'),
    // 'secret_key' => env('APP_KEY', '5h312iNc012pI_I7_'),
    'per_page' => 15,
    'max_upload_size' => 2, // Upload Size in MB
    'env' => env('APP_ENV', 'development'),
    'constants' => [
        'schedule' => ['closer' => ['time_in' => '11:30:00' , 'time_out' => '20:30:00']]
    ],
];