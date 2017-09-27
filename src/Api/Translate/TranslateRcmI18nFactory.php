<?php

namespace Reliv\RcmApiLib\Api\Translate;

use Interop\Container\ContainerInterface;
use RcmI18n\Service\ParameterizeTranslator;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class TranslateRcmI18nFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return TranslateRcmI18n
     */
    public function __invoke($container)
    {
        return new TranslateRcmI18n(
            $container->get(ParameterizeTranslator::class),
            $container->get(BuildStringParams::class)
        );
    }
}
