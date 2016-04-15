<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\DefaultResourceControllerOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\ResourceControllersOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ControllerBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ControllerBuilder extends AbstractControllerBuilder
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * ControllerBuilder constructor.
     *
     * @param DefaultResourceControllerOptions $defaultResourceControllerOptions
     * @param ResourceControllersOptions       $resourceControllersOptions
     * @param ServiceLocatorInterface          $serviceLocator
     */
    public function __construct(
        DefaultResourceControllerOptions $defaultResourceControllerOptions,
        ResourceControllersOptions $resourceControllersOptions,
        ServiceLocatorInterface $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
        parent::__construct(
            $defaultResourceControllerOptions,
            $resourceControllersOptions
        );
    }

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

        if (empty($controllerConfig)) {
            return $default;
        }

        if(empty($controllerConfig['service'])) {
            $controllerConfig = $this->defaultResourceControllerOptions->get('controller');
            return $controllerConfig['service'];
        }

        return $this->serviceLocator->get($controllerConfig['service']);
    }
}
