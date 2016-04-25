<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\BaseRouteModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * class ZfConfigRouteModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigRouteModelProvider implements RouteModelProvider
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var RouteModel
     */
    protected $routerModel;

    protected $config;

    /**
     * ConfigRouteModelProvider constructor.
     *
     * @param array $config
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager
    ) {
        $this->serviceManager = $serviceManager;
        $this->config = $config;
    }

    /**
     * buildServiceModelCollection
     *
     * @param array $serviceNames
     * @param array $serviceOptionsArrays
     *
     * @return RouteModel
     */
    protected function buildServiceModelCollection($serviceNames, $serviceOptionsArrays)
    {
        $services = [];
        foreach ($serviceNames as $serviceAlias => $serviceName) {
            $services[$serviceAlias] = $this->serviceManager->get($serviceName);
        }

        $serviceOptions = [];
        foreach ($serviceOptionsArrays as $serviceAlias => $serviceOptionsArray) {
            $serviceOptions[$serviceAlias] = new GenericOptions($serviceOptionsArray);
        }

        return new BaseRouteModel(
            $services,
            $serviceOptions
        );
    }

    /**
     * get
     *
     * @return RouteModel
     */
    public function get()
    {
        if (empty($this->routerModel)) {
            $this->routerModel = $this->buildServiceModelCollection(
                $this->config['Reliv\\RcmApiLib']['resource']['routeServiceNames'],
                $this->config['Reliv\\RcmApiLib']['resource']['routeOptions']
            );
        }

        return $this->routerModel;
    }
}
