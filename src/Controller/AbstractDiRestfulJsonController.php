<?php

namespace Reliv\RcmApiLib\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class AbstractDiRestfulJsonController extends AbstractRestfulJsonController
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator
    ) {
        parent::__construct($serviceLocator);
    }
}
