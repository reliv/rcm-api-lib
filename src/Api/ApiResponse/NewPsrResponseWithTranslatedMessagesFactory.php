<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewPsrResponseWithTranslatedMessagesFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return NewPsrResponseWithTranslatedMessages
     */
    public function __invoke($container)
    {
        return new NewPsrResponseWithTranslatedMessages(
            $container->get(WithApiMessage::class),
            $container->get(WithTranslatedApiMessages::class)
        );
    }
}
