<?php

namespace Reliv\RcmApiLib\Factory;

use Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InputFilterMessagesHydratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        return new InputFilterApiMessagesHydrator(
            $config['Reliv\\RcmApiLib']['InputFilterApiMessagesHydrator']['primaryMessage']
        );
    }
}
