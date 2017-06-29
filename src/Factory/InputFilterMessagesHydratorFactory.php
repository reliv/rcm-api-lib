<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator;
use Zend\ServiceManager\ServiceLocatorInterface;

class InputFilterMessagesHydratorFactory
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
