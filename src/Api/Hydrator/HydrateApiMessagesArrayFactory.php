<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesArrayFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return HydrateApiMessagesArray
     */
    public function __invoke($container)
    {
        return new HydrateApiMessagesArray();
    }
}
