<?php
/**
 * depenencies.php
 */
return [
    'invokables' => [
        // ApiMessageApiMessagesHydrator
        Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator::class =>
            Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator::class,

        // ArrayApiMessagesHydrator
        Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator::class =>
            Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator::class,

        // ExceptionApiMessagesHydrator
        Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator::class =>
            Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator::class,

        // StringApiMessagesHydrator
        Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator::class =>
            Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator::class,

        // PsrApiResponseBuilder
        Reliv\RcmApiLib\Service\PsrApiResponseBuilder::class =>
            Reliv\RcmApiLib\Service\PsrApiResponseBuilder::class
    ],
    'factories' => [
        /* MAIN HYDRATOR */
        \Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator::class =>
            \Reliv\RcmApiLib\Hydrator\CompositeApiMessagesHydratorFactory::class,

        /* @deprecated old MAIN HYDRATOR */
        'Reliv\RcmApiLib\Hydrator' =>
            \Reliv\RcmApiLib\Hydrator\CompositeApiMessagesHydratorFactory::class,

        // InputFilterApiMessagesHydrator
        Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator::class =>
            \Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydratorFactory::class,

        // PsrResponseService
        Reliv\RcmApiLib\Service\PsrResponseService::class =>
            \Reliv\RcmApiLib\Factory\PsrResponseServiceFactory::class,

        // ResponseService
        Reliv\RcmApiLib\Service\ResponseService::class =>
            \Reliv\RcmApiLib\Factory\ResponseServiceFactory::class,
    ],
];
