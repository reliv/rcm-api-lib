<?php

namespace Reliv\RcmApiLib;

use Zend\ConfigAggregator\ConfigAggregator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    public function __invoke()
    {
        $assetMiddleware = new AssetMiddlewareConfig();
        $rcmApiLib = new RcmApiLibConfig();
        $zf2Config = new Zf2Config();

        $configManager = new ConfigAggregator(
            [
                $assetMiddleware,
                $rcmApiLib,
                $zf2Config,
            ]
        );

        $config = $configManager->getMergedConfig();

        return $config;
    }
}
