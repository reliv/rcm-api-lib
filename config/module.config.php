<?php
return [
    /**
     * Configuration
     */
    'Reliv\\RcmApiLib' => [
        /**
         * CompositeApiMessageHydrators
         * ['{HydratorServiceName}' => {priority}]
         */
        'CompositeApiMessageHydrators' => [
            'Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator' => 5000,
            'Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator' => 4000,
            'Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator' => 3000,
            'Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator' => 2000,
            'Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator' => 1000,
        ],

        /**
         * InputFilterApiMessagesHydrator
         */
        'InputFilterApiMessagesHydrator' => [
            'primaryMessage' => 'Some information was missing or invalid',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator' =>
                'Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator' =>
                'Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator' =>
                'Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator' =>
                'Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator',
        ],
        'factories' => [
            /* MAIN HYDRATOR */
            'Reliv\RcmApiLib\Hydrator' =>
                'Reliv\RcmApiLib\Factory\CompositeApiMessageHydratorFactory',
            'Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator' =>
                'Reliv\RcmApiLib\Factory\InputFilterMessagesHydratorFactory',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-api-lib/' => __DIR__ . '/../public/rcm-api-lib/',
            ],
        ]
    ],
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
