<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesStringFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return HydrateApiMessagesString
     */
    public function __invoke($container)
    {
        return new HydrateApiMessagesString();
    }
}
