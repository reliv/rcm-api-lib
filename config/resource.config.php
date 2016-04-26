<?php
/**
 * resource.config.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
return [
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
                'description' => 'Create new resource',
                'httpVerb' => 'POST',
                'name' => 'create',
                'path' => '',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'upsert' => [
                'description' => 'Update or create a resource',
                'httpVerb' => 'PUT',
                'name' => 'upsert',
                'path' => '',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'exists' => [
                'description' => 'Check if a resource exists',
                'httpVerb' => 'GET',
                'name' => 'exists',
                'path' => '(?<id>[^/]+)/exists',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'count' => [
                'description' => 'Return number of resources',
                'httpVerb' => 'GET',
                'name' => 'count',
                'path' => 'count',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'findById' => [
                'description' => 'Find resource by ID',
                'httpVerb' => 'GET',
                'name' => 'findById',
                'path' => '^(?<id>[^/^count]+)[^/]*$',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'find' => [
                'description' => 'Find resources',
                'httpVerb' => 'GET',
                'name' => 'find',
                'path' => '',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'findOne' => [
                'description' => 'Find resources',
                'httpVerb' => 'GET',
                'name' => 'findOne',
                'path' => 'findOne',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'deleteById' => [
                'description' => 'Delete resource by ID',
                'httpVerb' => 'DELETE',
                'name' => 'deleteById',
                'path' => ':id',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
            'updateProperties' => [
                'description' => 'Update resource properties by ID',
                'httpVerb' => 'PUT',
                'name' => 'updateProperties',
                'path' => ':id',
                'preServiceNames' => [],
                'preServiceOptions' => [],
                'postServiceNames' => [],
                'postServiceOptions' => [],
            ],
        ],
        /* DEFAULT: Status to return if a method is not defined */
        'methodMissingStatus' => 404,
        /* Pre Controller Middleware  */
        /*
         * '{serviceAlias}' => '{serviceName}',
         */
        'preServiceNames' => [
            'JsonRequestFormat' => 'Reliv\RcmApiLib\Resource\Middleware\RequestFormat\JsonRequestFormat',
        ],
        /*
         * '{serviceAlias}' => [ '{optionKey}' => '{optionValue}' ],
         */
        'preServiceOptions' => [
        ],
        /*
         * '{serviceAlias}' => '{serviceName}',
         */
        'postServiceNames' => [
        ],
        /*
         * '{serviceAlias}' => [ '{optionKey}' => '{optionValue}' ],
         */
        'postServiceOptions' => [
        ],
        /*
         * '{serviceAlias}' => '{serviceName}',
         */
        'finalServiceNames' => [
            'JsonResponseFormat' => 'Reliv\RcmApiLib\Resource\Middleware\ResponseFormat\JsonResponseFormat',
            'XmlResponseFormat' => 'Reliv\RcmApiLib\Resource\Middleware\ResponseFormat\XmlResponseFormat',
            'DefaultResponseFormat' => 'Reliv\RcmApiLib\Resource\Middleware\ResponseFormat\JsonResponseFormat',
        ],
        /*
         * '{serviceAlias}' => [ '{optionKey}' => '{optionValue}' ],
         */
        'finalServiceOptions' => [
            'JsonResponseFormat' => [
                'accepts' => [
                    'application/json'
                ],
            ],
            'XmlResponseFormat' => [
                'accepts' => [
                    'application/xml'
                ],
            ],
            'DefaultResponseFormat' => [
                'accepts' => [
                    '*/*'
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
                'count',
                'findById',
                'exists'
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
                //'RcmUserAcl' => 'Reliv\RcmApiLib\Resource\Middleware\Acl\RcmUserAcl',
                //'ZfInputFilterClass' => 'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterClass',
                //'ZfInputFilterConfig' => 'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterConfig',
                //'ZfInputFilterService' => 'Reliv\RcmApiLib\Resource\Middleware\InputFilter\ZfInputFilterService',
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
        ],
    ],
    /* DEFAULT: Route */
    'routeServiceNames' => [
        'baseRoute' => 'Reliv\RcmApiLib\Resource\Middleware\Route',
    ],
    'routeOptions' => [],
];
