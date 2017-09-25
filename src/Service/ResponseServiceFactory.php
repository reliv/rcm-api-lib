<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Service\ResponseService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return ResponseService
     */
    public function __invoke($container)
    {
        return new ResponseService(
            $container,
            $container->get(Translate::class)
        );
    }
}
