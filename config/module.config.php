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
                'controllerServiceName' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                'controllerOptions' => [
                    'entity' => null,
                ],
                /* DEFAULT: Resource Controller Method Definitions */
                'methods' => [
                    /* Default Methods */
                    'create' => [
                        'description' => "Create new resource",
                        'httpVerb' => 'POST',
                        'name' => 'create',
                        'path' => "",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'upsert' => [
                        'description' => "Update or create a resource",
                        'httpVerb' => 'PUT',
                        'name' => 'upsert',
                        'path' => "",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'exists' => [
                        'description' => "Check if a resource exists",
                        'httpVerb' => 'GET',
                        'name' => 'exists',
                        'path' => ":id/exists",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'findById' => [
                        'description' => "Find resource by ID",
                        'httpVerb' => 'GET',
                        'name' => 'findById',
                        'path' => "(?<id>[^/]+)",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'find' => [
                        'description' => "Find resources",
                        'httpVerb' => 'GET',
                        'name' => 'find',
                        'path' => "",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'findOne' => [
                        'description' => "Find resources",
                        'httpVerb' => 'GET',
                        'name' => 'findOne',
                        'path' => "findOne",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'deleteById' => [
                        'description' => "Delete resource by ID",
                        'httpVerb' => 'DELETE',
                        'name' => 'deleteById',
                        'path' => ":id",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'count' => [
                        'description' => "Count resources",
                        'httpVerb' => 'GET',
                        'name' => 'count',
                        'path' => "count",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                    'updateProperties' => [
                        'description' => "Update resource properties by ID",
                        'httpVerb' => 'PUT',
                        'name' => 'updateProperties',
                        'path' => ":id",
                        'preServiceNames' => [],
                        'preServiceOptions' => [],
                    ],
                ],
                /* DEFAULT: Status to return if a method is not defined */
                'methodMissingStatus' => 404,
                /* Pre Controller Middleware  */
                'preServiceNames' => [
                    // '{serviceAlias}' => '{serviceName}',
                ],
                'preServiceOptions' => [
                    // '{serviceAlias}' => [ '{optionKey}' => '{optionValue}' ],
                ],
                /* DEFAULT: Response Format */
                'responseFormatServiceName' => 'Reliv\RcmApiLib\Resource\ResponseFormat\CompositeResponseFormat',
                'responseFormatOptions' => [
                    'JsonResponseFormat' => [
                        'serviceName' => 'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat',
                        'validContentTypes' => [
                            'application/json'
                        ],
                    ],
                    'XmlResponseFormat' => [
                        'serviceName' => 'Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat',
                        'validContentTypes' => [
                            'application/xml'
                        ],
                    ],
                ],
            ],

            /**
             *
             */
            'resources' => [
                'example-path' => [
                    /* Methods White-list */
                    'methodsAllowed' => [
                        'findById',
                        'example',
                    ],
                    /* Resource Controller */
                    'controllerServiceName' => 'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController',
                    'controllerOptions' => [
                        'entity' => 'Rcm\Entity\Language',
                    ],
                    /* Resource Controller Method Definitions */
                    'methods' => [
                        //'example' => [
                        //],
                    ],
                    /* */
                    'methodMissingStatus' => 404,
                    /* Path */
                    'path' => 'example-path',
                    /* Pre Controller Middleware */
                    'preServiceNames' => [
                        //'RcmUserAcl' => 'Reliv\RcmApiLib\Resource\Middleware\RcmUserAcl',
                        //'ZfInputFilterClass' => 'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterClass',
                        //'ZfInputFilterConfig' => 'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterConfig',
                        //'ZfInputFilterService' => 'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterService',
                    ],
                    'preServiceOptions' => [
                        'RcmUserAcl' => [
                            'resourceId' => '{resourceId}',
                            'privilege' => null,
                        ],
                        'ZfInputFilterClass' => [
                            'class' => '',
                        ],
                        'ZfInputFilterService' => [
                            'serviceName' => '',
                        ],
                        'ZfInputFilterConfig' => [
                            'config' => [],
                        ],
                    ],
//                    'responseFormatServiceName' => 'Reliv\RcmApiLib\Resource\ResponseFormat\JsonResponseFormat',
//                    'responseFormatOptions' => [
//                        'validContentTypes' => [
//                            'application/json'
//                        ],
//                    ],
                ],
            ],
            /* DEFAULT: Route */
            'routeServiceName' => 'Reliv\RcmApiLib\Resource\Route\RegexRoute',
            'routeOptions' => [
                'path' => '/api/resource/(?<resourceController>[^/]+)/(?<resourceMethod>[^.]*)',
                'routeParams' => [],
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
            'Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder' => [
                'class' => 'Reliv\RcmApiLib\Resource\Builder\ZfConfigResourceModelBuilder',
                'arguments' => [
                    'Config',
                    'ServiceManager',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Builder\ResponseFormatModelBuilder' => [
                'class' => 'Reliv\RcmApiLib\Resource\Builder\ZfConfigResponseFormatModelBuilder',
                'arguments' => [
                    'Config',
                    'ServiceManager',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Builder\RouteModelBuilder' => [
                'class' => 'Reliv\RcmApiLib\Resource\Builder\ZfConfigRouteModelBuilder',
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
            'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware' => [
                'class' => 'Reliv\RcmApiLib\Resource\Middleware\MainMiddleware',
                'arguments' => [
                    'Reliv\RcmApiLib\Resource\Builder\RouteModelBuilder',
                    'Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder',
                    'Reliv\RcmApiLib\Resource\Builder\ResponseFormatModelBuilder',
                ],
            ],
            'Reliv\RcmApiLib\Resource\Middleware\RcmUserAcl' => [
                
            ],
            'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterClass' => [

            ],
            'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterConfig' => [

            ],
            'Reliv\RcmApiLib\Resource\Middleware\ZfInputFilterService' => [

            ],
            /* Resource ResponseFormat */
            'Reliv\RcmApiLib\Resource\ResponseFormat\ZfCompositeResponseFormat' => [
                'class' => 'Reliv\RcmApiLib\Resource\ResponseFormat\ZfCompositeResponseFormat\'',
                'arguments' => [
                    'ServiceManager',
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
