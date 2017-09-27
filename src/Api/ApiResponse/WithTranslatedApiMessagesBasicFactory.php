<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithTranslatedApiMessagesBasicFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return WithTranslatedApiMessagesBasic
     */
    public function __invoke($container)
    {
        return new WithTranslatedApiMessagesBasic(
            $container->get(WithTranslatedApiMessage::class)
        );
    }
}
