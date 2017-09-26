<?php

namespace Reliv\RcmApiLib;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssetMiddlewareConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    \Reliv\RcmApiLib\Middleware\AssetController::class
                    => \Reliv\RcmApiLib\Middleware\AssetController::class,
                ],
            ],
            /**
             * routes
             */
            'routes' => [
                'modules.rcm-api-lib.public' => [
                    'name' => 'modules.rcm-api-lib.public',
                    'path' => '/modules/rcm-api-lib/{fileName:.*}',
                    'middleware' => \Reliv\RcmApiLib\Middleware\AssetController::class,
                    'options' => [],
                    'allowed_methods' => ['GET'],
                ],
            ],
        ];
    }
}
