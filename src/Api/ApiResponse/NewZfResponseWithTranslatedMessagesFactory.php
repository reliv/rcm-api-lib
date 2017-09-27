<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewZfResponseWithTranslatedMessagesFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return NewZfResponseWithTranslatedMessages
     */
    public function __invoke($container)
    {
        return new NewZfResponseWithTranslatedMessages(
            $container->get(WithApiMessage::class),
            $container->get(WithTranslatedApiMessages::class)
        );
    }
}
