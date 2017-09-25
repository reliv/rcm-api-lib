<?php

namespace Reliv\RcmApiLib\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompositeApiMessagesHydratorFactory
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
