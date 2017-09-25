<?php

namespace Reliv\RcmApiLib\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InputFilterApiMessagesHydratorFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return InputFilterApiMessagesHydrator
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');

        return new InputFilterApiMessagesHydrator(
            $config['Reliv\\RcmApiLib']['InputFilterApiMessagesHydrator']['primaryMessage']
        );
    }
}
