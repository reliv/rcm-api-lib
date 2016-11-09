<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Service\PsrResponseService;
use Reliv\RcmApiLib\Service\PsrApiResponseBuilder;

/**
 * Class ServicePrsResponseServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServicePrsResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PsrResponseService
     */
    public function __invoke($container)
    {
        return new PsrResponseService(
            $container,
            $container->get('RcmI18n\Service\ParameterizeTranslator'),
            $container->get(PsrApiResponseBuilder::class)
        );
    }
}
