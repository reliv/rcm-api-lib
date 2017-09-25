<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewPsrFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return NewPsr
     */
    public function __invoke($container)
    {
        return new NewPsr(
            $container->get(WithApiMessage::class),
            $container->get(WithTranslatedApiMessages::class)
        );
    }
}
