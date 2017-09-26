<?php

namespace Reliv\RcmApiLib;

use Zend\ConfigAggregator\ConfigAggregator;

/**
 * ZF2 Module
 */
class Module
{
    public function getConfig()
    {
        $assetManager = new AssetManagerConfig();
        $rcmApiLib = new RcmApiLibConfig();
        $zf2Config = new Zf2Config();

        $configManager = new ConfigAggregator(
            [
                $assetManager,
                $rcmApiLib,
                $zf2Config,
            ]
        );

        $config = $configManager->getMergedConfig();

        $config['service_manager'] = array_merge($config['service_manager'], $config['dependencies']);

        return $config;
    }
}
