<?php
/**
 * routes.php
 */
return [
    'modules.rcm-api-lib.public' => [
        'name' => 'modules.rcm-api-lib.public',
        'path' => '/modules/rcm-api-lib/{fileName:.*}',
        'middleware' => \Reliv\RcmApiLib\Middleware\AssetController::class,
        'options' => [
            'test' => 'adsf',
        ],
        'allowed_methods' => ['GET'],
    ],
];
