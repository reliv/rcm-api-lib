<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydrateApiMessagesCompositeFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return HydrateApiMessagesComposite
     */
    public function __invoke($container)
    {
        $composite = new HydrateApiMessagesComposite();
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
