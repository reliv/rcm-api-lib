<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesApiMessageFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return HydrateApiMessagesApiMessage
     */
    public function __invoke($container)
    {
        return new HydrateApiMessagesApiMessage();
    }
}
