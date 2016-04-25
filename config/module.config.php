<?php
return [
    /**
     * Configuration
     */
    'Reliv\\RcmApiLib' => [
        'CompositeApiMessageHydrators' => [
            'Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator',
            'Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator',
        ],
        'InputFilterApiMessagesHydrator' => [
            'primaryMessage' => 'Some information was missing or invalid',
        ],
        /* */
        'resource' => require __DIR__ . '/resource.config.php',
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
        'config_factories' => [
            /* Resource Providers */
            'Reliv\RcmApiLib\Resource\Provider\ResourceModelProvider' => [
                'class' => 'Reliv\RcmApiLib\Resource\Provider\ZfConfigResourceModelProvider',
                'arguments' => [
                    'Config',
                    'ServiceManager',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Provider\RouteModelProvider' => [
                'class' => 'Reliv\RcmApiLib\Resource\Provider\ZfConfigRouteModelProvider',
                'arguments' => [
                    'Config',
                    'ServiceManager',
                ],
            ],
            /* Resource Controller */
            'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController' => [
                'arguments' => [
                    'Doctrine\ORM\EntityManager',
                ],
            ],
            /* Resource Middleware */
            // ACL
            'Reliv\RcmApiLib\Resource\Middleware\Acl\RcmUserAcl' => [
            ],
            // InputFilter
            'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterClass' => [
            ],
            'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterConfig' => [
            ],
            'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterService' => [
            ],
            // Request Formatter
            'Reliv\RcmApiLib\Resource\Middleware\RequestFormat\JsonRequestFormat' =>[
            ],
            // Response Formatter
            'Reliv\RcmApiLib\Resource\ResponseFormat\Middleware\JsonResponseFormat' => [
            ],
            'Reliv\RcmApiLib\Resource\ResponseFormat\Middleware\XmlResponseFormat' => [
            ],
            // Main
            'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware' => [
                'class' => 'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware',
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Provider\RouteModelProvider',
                    'Reliv\RcmApiLib\Resource\Provider\ResourceModelProvider',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Middleware\Route' => [
                'class' => 'Reliv\RcmApiLib\Resource\Middleware\RegExRoute',
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Provider\RouteModelProvider',
                    'Reliv\RcmApiLib\Resource\Provider\ResourceModelProvider',
                ],
            ],
            /* Resource Route */
            'Reliv\RcmApiLib\Resource\Route\RegexRoute' => [
            ],
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
