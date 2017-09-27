<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesExceptionFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return HydrateApiMessagesException
     */
    public function __invoke($container)
    {
        return new HydrateApiMessagesException();
    }
}
