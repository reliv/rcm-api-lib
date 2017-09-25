<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Service\PsrApiResponseBuilder;
use Reliv\RcmApiLib\Service\PsrResponseService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PsrResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return PsrResponseService
     */
    public function __invoke($container)
    {
        return new PsrResponseService(
            $container,
            $container->get(Translate::class),
            $container->get(PsrApiResponseBuilder::class)
        );
    }
}
