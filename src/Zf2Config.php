<?php

namespace Reliv\RcmApiLib;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Zf2Config
{
    public function __invoke()
    {
        return [
            /**
             * service_manager
             */
            'service_manager' => [/* Use RcmApiLibConfig */],

            /* <Example> - TESTING ONLY *
            'controllers' => [
                'invokables' => [
                    'Reliv\RcmApiLib\Controller\ExampleRestfulJsonController' =>
                        'Reliv\RcmApiLib\Controller\ExampleRestfulJsonController',
                ],
            ],
            'router' => [
                'routes' => [
                    'Reliv\RcmApiLib\Example' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/rcm-api-lib/example[/:id]',
                            'defaults' => [
                                'controller' => 'Reliv\RcmApiLib\Controller\ExampleRestfulJsonController',
                                'id' => 'getApiMessage',
                            ],
                        ],
                    ],
                ],
            ],
            'view_manager' => [
                'strategies' => [
                    'ViewJsonStrategy',
                ],
            ],
            /* </Example>*/
        ];
    }





}
