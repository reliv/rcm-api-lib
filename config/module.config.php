<?php
return [
    /**
     * asset_manager
     */
    'asset_manager' => require(__DIR__ . '/asset_manager.php'),

    /**
     * Configuration
     */
    'Reliv\\RcmApiLib' =>require(__DIR__ . '/reliv.rcm-api-lib.php'),

    /**
     * service_manager
     */
    'service_manager' => require(__DIR__ . '/dependencies.php'),

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
