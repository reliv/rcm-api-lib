<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithTranslatedApiMessageBasicFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return WithTranslatedApiMessageBasic
     */
    public function __invoke($container)
    {
        return new WithTranslatedApiMessageBasic(
            $container->get(Translate::class)
        );
    }
}
