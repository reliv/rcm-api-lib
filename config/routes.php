<?php
/**
 * routes.php
 */
return [
    'modules.rcm-api-lib.public' => [
        'name' => 'modules.rcm-api-lib.public',
        'path' => '/modules/rcm-api-lib/{fileName:.*}',
        'middleware' => \Reliv\RcmApiLib\Middleware\AssetController::class,
        'options' => [],
        'allowed_methods' => ['GET'],
    ],
];
