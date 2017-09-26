<?php

namespace Reliv\RcmApiLib\Api\Translate;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildStringParamsFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return BuildStringParams
     */
    public function __invoke($container)
    {
        return new BuildStringParams();
    }
}
