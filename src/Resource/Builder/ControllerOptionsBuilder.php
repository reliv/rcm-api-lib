<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\ResourceControllersOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ControllerOptionsBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ControllerOptionsBuilder extends AbstractControllerBuilder
{
    /**
     * build
     *
     * @param string $resourceControllerKey
     * @param null   $default
     *
     * @return null
     */
    public function build($resourceControllerKey, $default = null)
    {
        $controllerOptions = $this->getControllerOptions($resourceControllerKey);

        $controllerConfig = $controllerOptions->get('controller');

        if (empty($controllerConfig) && empty($controllerConfig['options'])) {
            return $default;
        }

        return $controllerConfig['options'];
    }
}
