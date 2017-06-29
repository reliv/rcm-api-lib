<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Hydrator\CompositeApiMessagesHydrator;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompositeApiMessageHydratorFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return CompositeApiMessagesHydrator
     */
    public function __invoke($container)
    {
        $composite = new CompositeApiMessagesHydrator();
        $config = $container->get('Config');

        $queue = new \SplPriorityQueue();

        foreach ($config['Reliv\\RcmApiLib']['CompositeApiMessageHydrators'] as $hydratorService => $priority) {
            $queue->insert($hydratorService, $priority);
        }

        foreach ($queue as $hydratorService) {
            $composite->add($container->get($hydratorService));
        }

        return $composite;
    }
}
