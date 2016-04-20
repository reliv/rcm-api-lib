<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\BaseRouteModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Route\Route;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * class ZfConfigRouteModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigRouteModelBuilder implements RouteModelBuilder
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var string
     */
    protected $serviceAlias;

    /**
     * @var Route
     */
    protected $routeService;

    /**
     * @var Options
     */
    protected $routeOptions;

    /**
     * ConfigRouteModelBuilder constructor.
     *
     * @param array                   $config
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager
    ) {
        $this->config = $config;
        $this->serviceManager = $serviceManager;

        $this->serviceAlias = $config['Reliv\\RcmApiLib']['resource']['routeServiceName'];

        $this->routeService = $serviceManager->get(
            $this->serviceAlias
        );

        $this->routeOptions = new GenericOptions(
            $config['Reliv\\RcmApiLib']['resource']['routeOptions']
        );
    }

    /**
     * build
     *
     * @return RouteModel
     */
    public function build()
    {
        return new BaseRouteModel(
            $this->serviceAlias,
            $this->routeService,
            $this->routeOptions
        );
    }
}
