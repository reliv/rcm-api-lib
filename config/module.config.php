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
        'resource' => [
            /**
             *
             */
            'config' => [
                //'basePath' => '/api/resource/{resourceControllerMethod}', // /:resourcePath
                'basePath' => '/api/resource/(?<resourceController>[a-z]+)/(?<resourceMethod>[^.]+)',
                'baseFormat' => 'application/json',
                'undefinedMethodStatus' => 404,
            ],

            'formats' => [
                'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat'
            ],

            'defaultMethods' => [
                /* Default Methods */
                'create' => [
                    'description' => "Create new resource",
                    'httpVerb' => 'POST',
                    'path' => ""
                ],
                'upsert' => [
                    'description' => "Update or create a resource",
                    'httpVerb' => 'PUT',
                    'path' => ""
                ],
                'exists' => [
                    'description' => "Check if a resource exists",
                    'httpVerb' => 'GET',
                    'path' => ":id/exists"
                ],
                'findById' => [
                    'description' => "Find resource by ID",
                    'httpVerb' => 'GET',
                    'path' => ":id"
                ],
                'find' => [
                    'description' => "Find resources",
                    'httpVerb' => 'GET',
                    'path' => ""
                ],
                'findOne' => [
                    'description' => "Find resources",
                    'httpVerb' => 'GET',
                    'path' => "findOne"
                ],
                'deleteById' => [
                    'description' => "Delete resource by ID",
                    'httpVerb' => 'DELETE',
                    'path' => ":id"
                ],
                'count' => [
                    'description' => "Count resources",
                    'httpVerb' => 'GET',
                    'path' => "count"
                ],
                'updateProperties' => [
                    'description' => "Udpate resource properties by ID",
                    'httpVerb' => 'PUT',
                    'path' => ":id"
                ],
            ],
            /**
             *
             */
            'controllerRoute' => [
                '/example-path' => [
                    'allowedMethods' => [
                        'get'
                    ],
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
                    /**
                     * http service
                     */
                    'controller' => [
                        'service' => 'MyHttpRepositoryServiceName',
                        'options' => [],
                    ],
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
                    'path' => '/example-path'
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
            'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware' => [
                'arguments' => [
                    'Config',
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
