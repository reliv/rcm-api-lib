<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessages;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithApiMessageBasicFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return WithApiMessageBasic
     */
    public function __invoke($container)
    {
        return new WithApiMessageBasic(
            $container->get(HydrateApiMessages::class)
        );
    }
}
