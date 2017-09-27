<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithApiMessagesBasicFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return WithApiMessagesBasic
     */
    public function __invoke($container)
    {
        return new WithApiMessagesBasic(
            $container->get(WithApiMessage::class)
        );
    }
}
