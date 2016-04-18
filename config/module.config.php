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
                /* DEFAULT: Resource Controller */
                'controllerService' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                'controllerOptions' => [
                    'entity' => null,
                ],
                /* DEFAULT: Resource Controller Method Definitions */
                'methods' => [
                    /* Default Methods */
                    'create' => [
                        'description' => "Create new resource",
                        'httpVerb' => 'POST',
                        'path' => "",
                        'preService' => [],
                    ],
                    'upsert' => [
                        'description' => "Update or create a resource",
                        'httpVerb' => 'PUT',
                        'path' => "",
                        'preService' => [],
                    ],
                    'exists' => [
                        'description' => "Check if a resource exists",
                        'httpVerb' => 'GET',
                        'path' => ":id/exists",
                        'preService' => [],
                    ],
                    'findById' => [
                        'description' => "Find resource by ID",
                        'httpVerb' => 'GET',
                        'path' => ":id",
                        'preService' => [],
                    ],
                    'find' => [
                        'description' => "Find resources",
                        'httpVerb' => 'GET',
                        'path' => "",
                        'preService' => [],
                    ],
                    'findOne' => [
                        'description' => "Find resources",
                        'httpVerb' => 'GET',
                        'path' => "findOne",
                        'preService' => [],
                    ],
                    'deleteById' => [
                        'description' => "Delete resource by ID",
                        'httpVerb' => 'DELETE',
                        'path' => ":id",
                        'preService' => [],
                    ],
                    'count' => [
                        'description' => "Count resources",
                        'httpVerb' => 'GET',
                        'path' => "count",
                        'preService' => [],
                    ],
                    'updateProperties' => [
                        'description' => "Update resource properties by ID",
                        'httpVerb' => 'PUT',
                        'path' => ":id",
                        'preService' => [],
                    ],
                ],
                /* DEFAULT: Status to return if a method is not defined */
                'missingMethodStatus' => 404,
                /* Pre Controller Middleware  */
                'preServices' => [
                    // '{serviceName}',
                ],
                'preOptions' => [
                    //'serviceName' => [ '{optionKey}' => '{optionValue}' ],
                ],
                /* DEFAULT: Response Format */
                'responseFormatService' => 'Reliv\RcmApiLib\Resource\ResponseFormat\CompositeResponseFormat',
                'responseFormatOptions' => [
                    'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat' => [
                        'validContentTypes' => [
                            'application/json'
                        ],
                    ],
                    'Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat' => [
                        'validContentTypes' => [
                            'application/xml'
                        ],
                    ],
                ],
                /* DEFAULT: Route */
                'routeService' => 'Reliv\RcmApiLib\Resource\Route\RegexRoute',
                'routeOptions' => [
                    'path' => '/api/resource/(?<resourceController>[a-z]+)/(?<resourceMethod>[^.]+)',
                ],
            ],

            /**
             *
             */
            'resources' => [
                '/example-path' => [
                    /* Methods White-list */
                    'allowedMethods' => [
                        'get',
                        'example',
                    ],
                    /* Resource Controller */
                    'controllerService' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                    'controllerOptions' => [
                        'entity' => null,
                    ],
                    /* Resource Controller Method Definitions */
                    'methods' => [
                        'example' => [
                            /* Pre Controller Method Middleware  */
                            'preServices' => [
                                'MyHttpServiceName' => [
                                    // Options
                                ]
                            ],
                            /* */
                            'description' => "My description",
                            'httpVerb' => 'GET',
                            'path' => "/:id"
                        ],
                    ],
                    /* */
                    'missingMethodStatus' => 404,
                    /* Path */
                    'path' => '/example-path',
                    /* Pre Controller Middleware */
                    'preServices' => [
                        'Reliv\RcmApiLib\Resource\Middleware\RcmUserAcl',
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterClass',
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterService',
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterConfig',
                    ],
                    'preOptions' => [
                        'Reliv\RcmApiLib\Resource\Middleware\RcmUserAcl' => [
                            'resourceId' => '{resourceId}',
                            'privilege' => null,
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterClass' => [
                            'class' => '',
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterService' => [
                            'service' => '',
                        ],
                        'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterConfig' => [
                            'config' => [],
                        ],
                    ],
                    'responseFormatService' => 'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat',
                    'responseFormatOptions' => [
                        'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat' => [
                            'validContentTypes' => [
                                'application/json'
                            ],
                        ],
                        'Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat' => [
                            'validContentTypes' => [
                                'application/xml'
                            ],
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
            /* Resource Builders */
            'Reliv\RcmApiLib\Resource\Builder\ConfigResourceModelBuilder' => [
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Options\DefaultResourceControllerOptions',
                    'Reliv\RcmApiLib\Resource\Options\ResourceControllersOptions',
                ],
            ],
            /* Resource Controller */
            'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController' => [
                'arguments' => [
                    'Doctrine\ORM\EntityManager',
                ],
            ],

            /* Resource Middleware */
            'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware' => [
                'arguments' => [
                    'Config',
                    'ServiceManager',
                    'Reliv\RcmApiLib\Resource\Builder\ConfigResourceModelBuilder',
                    'Reliv\RcmApiLib\Resource\Route\RegexRoute'
                ],
            ],

            /* Resource Options */
            'Reliv\RcmApiLib\Resource\Options\DefaultOptions' => [
                'arguments' => [
                    'Config',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Options\ResourcesOptions' => [
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
                    'XmlResponseFormat' => [
                        'add',
                        ['Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat'],
                    ],
                ],
            ],
            'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat' => [
            ],
            'Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat' => [
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
