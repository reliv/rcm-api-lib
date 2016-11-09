<?php

namespace Reliv\RcmApiLib\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Service\ResponseService;

/**
 * Class ServiceResponseServiceFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ServiceResponseServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return ResponseService
     */
    public function __invoke($container)
    {
        return new ResponseService(
            $container,
            $container->get('RcmI18n\Service\ParameterizeTranslator')
        );
    }
}
