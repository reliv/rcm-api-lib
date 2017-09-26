<?php

namespace Reliv\RcmApiLib;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssetManagerConfig
{
    public function __invoke()
    {
        return [
            /**
             * asset_manager
             */
            'asset_manager' => [
                'resolver_configs' => [
                    'aliases' => [
                        'modules/rcm-api-lib/' => __DIR__ . '/../public/rcm-api-lib/',
                    ],
                ],
            ],
        ];
    }
}
