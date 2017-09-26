<?php

namespace Reliv\RcmApiLib\Api\Translate;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class TranslateZf2Factory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return TranslateZf2
     */
    public function __invoke($container)
    {
        return new TranslateZf2(
            $container->get('MvcTranslator'),
            $container->get(BuildStringParams::class)
        );
    }
}
