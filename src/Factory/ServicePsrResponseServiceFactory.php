<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Service\PsrApiResponseBuilder;
use Reliv\RcmApiLib\Service\PsrResponseService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServicePsrResponseServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServicePsrResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface
     *
     * @return PsrResponseService
     */
    public function __invoke($container)
    {
        return new PsrResponseService(
            $container,
            $container->get(\RcmI18n\Service\ParameterizeTranslator::class),
            $container->get(PsrApiResponseBuilder::class)
        );
    }
}
