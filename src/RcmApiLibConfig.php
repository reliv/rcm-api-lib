<?php

namespace Reliv\RcmApiLib;

use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessagesFactory;
use Reliv\RcmApiLib\Api\ApiResponse\NewZfResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Api\ApiResponse\NewZfResponseWithTranslatedMessagesFactory;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessage;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessageBasicFactory;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessages;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessagesBasicFactory;
use Reliv\RcmApiLib\Api\ApiResponse\WithTranslatedApiMessage;
use Reliv\RcmApiLib\Api\ApiResponse\WithTranslatedApiMessageBasicFactory;
use Reliv\RcmApiLib\Api\ApiResponse\WithTranslatedApiMessages;
use Reliv\RcmApiLib\Api\ApiResponse\WithTranslatedApiMessagesBasicFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessages;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesApiMessage;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesApiMessageFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesArray;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesArrayFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesCompositeFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesDefault;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesDefaultFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesException;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesExceptionFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesInputFilter;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesInputFilterFactory;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesString;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesStringFactory;
use Reliv\RcmApiLib\Api\Translate\BuildStringParams;
use Reliv\RcmApiLib\Api\Translate\BuildStringParamsFactory;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Api\Translate\TranslateRcmI18nFactory;
use Reliv\RcmApiLib\Service\PsrApiResponseBuilder;
use Reliv\RcmApiLib\Service\PsrResponseService;
use Reliv\RcmApiLib\Service\PsrResponseServiceFactory;
use Reliv\RcmApiLib\Service\ResponseService;
use Reliv\RcmApiLib\Service\ResponseServiceFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RcmApiLibConfig
{
    public function __invoke()
    {
        return [
            /**
             * dependencies
             */
            'dependencies' => [
                'invokables' => [
                    // PsrApiResponseBuilder
                    PsrApiResponseBuilder::class
                    => PsrApiResponseBuilder::class
                ],
                'factories' => [
                    /**
                     * Api ApiResponse ===================================
                     */
                    NewPsrResponseWithTranslatedMessages::class
                    => NewPsrResponseWithTranslatedMessagesFactory::class,

                    NewZfResponseWithTranslatedMessages::class
                    => NewZfResponseWithTranslatedMessagesFactory::class,

                    WithApiMessage::class
                    => WithApiMessageBasicFactory::class,

                    WithApiMessages::class
                    => WithApiMessagesBasicFactory::class,

                    WithTranslatedApiMessage::class
                    => WithTranslatedApiMessageBasicFactory::class,

                    WithTranslatedApiMessages::class
                    => WithTranslatedApiMessagesBasicFactory::class,

                    /**
                     * Apil HydrateApiMessages ===================================
                     */
                    /* MAIN HYDRATOR */
                    HydrateApiMessages::class
                    => HydrateApiMessagesCompositeFactory::class,

                    // ApiMessageApiMessagesHydrator
                    HydrateApiMessagesApiMessage::class
                    => HydrateApiMessagesApiMessageFactory::class,

                    // ArrayApiMessagesHydrator
                    HydrateApiMessagesArray::class
                    => HydrateApiMessagesArrayFactory::class,

                    HydrateApiMessagesDefault::class
                    => HydrateApiMessagesDefaultFactory::class,

                    // ExceptionApiMessagesHydrator
                    HydrateApiMessagesException::class
                    => HydrateApiMessagesExceptionFactory::class,

                    // InputFilterApiMessagesHydrator
                    HydrateApiMessagesInputFilter::class
                    => HydrateApiMessagesInputFilterFactory::class,

                    /**
                     * Api ApiResponse ===================================
                     */
                    BuildStringParams::class
                    => BuildStringParamsFactory::class,

                    /**
                     * Translate using RcmI18n by default
                     *
                     * Over-ride this as needed
                     */
                    Translate::class
                    => TranslateRcmI18nFactory::class,

                    // StringApiMessagesHydrator
                    HydrateApiMessagesString::class
                    => HydrateApiMessagesStringFactory::class,

                    // PsrResponseService
                    PsrResponseService::class
                    => PsrResponseServiceFactory::class,

                    // ResponseService
                    ResponseService::class
                    => ResponseServiceFactory::class,
                ],
            ],

            /**
             * Configuration
             */
            'Reliv\\RcmApiLib' => [
                /**
                 * CompositeApiMessageHydrators
                 * ['{HydratorServiceName}' => {priority}]
                 */
                'CompositeApiMessageHydrators' => [
                    HydrateApiMessagesApiMessage::class => 5000,
                    HydrateApiMessagesArray::class => 4000,
                    HydrateApiMessagesException::class => 3000,
                    HydrateApiMessagesInputFilter::class => 2000,
                    HydrateApiMessagesString::class => 1000,
                    HydrateApiMessagesDefault::class => 100,
                ],

                /**
                 * InputFilterApiMessagesHydrator
                 */
                'InputFilterApiMessagesHydrator' => [
                    'primaryMessage' => 'Some information was missing or invalid',
                ],
            ],
        ];
    }
}
