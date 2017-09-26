<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesInputFilterFactory
{
    /**
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return HydrateApiMessagesInputFilter
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');

        return new HydrateApiMessagesInputFilter(
            $config['Reliv\\RcmApiLib']['InputFilterApiMessagesHydrator']['primaryMessage']
        );
    }
}
