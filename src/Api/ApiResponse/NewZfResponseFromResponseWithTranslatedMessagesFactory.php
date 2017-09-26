<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewZfResponseFromResponseWithTranslatedMessagesFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return NewZfResponseFromResponseWithTranslatedMessages
     */
    public function __invoke($container)
    {
        return new NewZfResponseFromResponseWithTranslatedMessages(
            $container->get(WithApiMessage::class),
            $container->get(WithTranslatedApiMessages::class)
        );
    }
}
