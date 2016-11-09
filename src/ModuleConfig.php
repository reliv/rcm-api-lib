<?php

namespace Reliv\RcmApiLib;

/**
 * Class ModuleConfig
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ModuleConfig
{
    public function __invoke()
    {
        return [
            /**
             * dependencies
             */
            'dependencies' => require(__DIR__ . '/../config/dependencies.php'),

            /**
             * Configuration
             */
            'Reliv\\RcmApiLib' =>require(__DIR__ . '/../config/reliv.rcm-api-lib.php'),

            /**
             * routes
             */
            'routes' => require(__DIR__ . '/../config/routes.php'),
        ];
    }
}
