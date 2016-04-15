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
        'resource' => [
            'default' => [
                /* */
                'basePath' => '/api/resource/(?<resourceController>[a-z]+)/(?<resourceMethod>[^.]+)',

                'controllerOptions' => [
                    'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController' => [
                        'entity' => null,
                    ],
                ],
                /* */
                'responseFormatOptions' => [
                    'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat' => [
                        'validContentTypes' => [
                            'application/json'
                        ]
                    ],
                ],
                /* */
                'routeOptions' => [
                    'Reliv\RcmApiLib\Resource\Route\RegexRoute' => [
                        'path' => null,
                    ],
                ],
                /* */
                'resourceController' => [
                    /* */
                    'allowedMethods' => [
                    ],
                    /* */
                    'controller' => [
                        'service' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                        'options' => [],
                    ],
                    'responseFormat' => [
                        'service' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                        'options' => [],
                    ],
                    /* */
                    'methods' => [
                        /* Default Methods */
                        'create' => [
                            'description' => "Create new resource",
                            'httpVerb' => 'POST',
                            'path' => "",
                            'pre' => [],
                        ],
                        'upsert' => [
                            'description' => "Update or create a resource",
                            'httpVerb' => 'PUT',
                            'path' => "",
                            'pre' => [],
                        ],
                        'exists' => [
                            'description' => "Check if a resource exists",
                            'httpVerb' => 'GET',
                            'path' => ":id/exists",
                            'pre' => [],
                        ],
                        'findById' => [
                            'description' => "Find resource by ID",
                            'httpVerb' => 'GET',
                            'path' => ":id",
                            'pre' => [],
                        ],
                        'find' => [
                            'description' => "Find resources",
                            'httpVerb' => 'GET',
                            'path' => "",
                            'pre' => [],
                        ],
                        'findOne' => [
                            'description' => "Find resources",
                            'httpVerb' => 'GET',
                            'path' => "findOne",
                            'pre' => [],
                        ],
                        'deleteById' => [
                            'description' => "Delete resource by ID",
                            'httpVerb' => 'DELETE',
                            'path' => ":id",
                            'pre' => [],
                        ],
                        'count' => [
                            'description' => "Count resources",
                            'httpVerb' => 'GET',
                            'path' => "count",
                            'pre' => [],
                        ],
                        'updateProperties' => [
                            'description' => "Udpate resource properties by ID",
                            'httpVerb' => 'PUT',
                            'path' => ":id",
                            'pre' => [],
                        ],
                    ],
                    /* */
                    'path' => null,
                    /* */
                    'pre' => [
                    ],
                    'missingMethodStatus' => 404,
                ],
            ],

            /**
             *
             */
            'resourceControllers' => [
                '/example-path' => [
                    /* */
                    'allowedMethods' => [
                        'get'
                    ],
                    /* */
                    'controller' => [
                        'service' => 'MyHttpRepositoryServiceName',
                        'options' => [],
                    ],
                    /* */
                    'methods' => [
                        'example' => [
                            'pre' => [
                                'MyHttpServiceName' => [
                                    // Options
                                ]
                            ],
                            'description' => "My description",
                            'httpVerb' => 'GET',
                            'path' => "/:id"
                        ],
                    ],
                    /* */
                    'path' => '/example-path',
                    /* */
                    'pre' => [
                        'MyHttpServiceName' => [
                            // Options
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware/RcmUserAcl' => [
                            'resourceId' => '{resourceId}',
                            'privilege' => null,
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware/ZfInputFilterClass' => [
                            'class' => '',
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware/ZfInputFilterService' => [
                            'service' => '',
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware/ZfInputFilterConfig' => [
                            'config' => [],
                        ],
                    ],
                ],
            ],
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
        'config_factories' => [
            /* Resource Controller */
            'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController' => [
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Options\DefaultControllerOptions',
                    'Doctrine\ORM\EntityManager',
                ],
            ],

            /* Resource Middleware */
            'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware' => [
                'arguments' => [
                    'Config',
                ],
            ],

            /* Resource Options */
            'Reliv\RcmApiLib\Resource\Options\DefaultControllerOptions' => [
                'arguments' => [
                    'Config',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Options\DefaultResourceControllerOptions' => [
                'arguments' => [
                    'Config',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Options\DefaultResponseFormatOptions' => [
                'arguments' => [
                    'Config',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Options\DefaultRouteOptions' => [
                'arguments' => [
                    'Config',
                ],
            ],

            /* Resource ResponseFormat */
            'Reliv\RcmApiLib\Resource\ResponseFormat\CompositeResponseFormat' => [
                'class' => 'Reliv\RcmApiLib\Resource\ResponseFormat\CompositeResponseFormat\'',
                'calls' => [
                    'JsonResponseFormat' => [
                        'add',
                        ['Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat'],
                    ],
                ],
            ],
            'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat' => [
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Options\DefaultResponseFormatOptions',
                ],
            ],

            /* Resource Route */
            'Reliv\RcmApiLib\Resource\Route\RegexRoute' => [
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Options\DefaultRouteOptions',
                ],
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
