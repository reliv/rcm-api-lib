<?php

namespace Reliv\RcmApiLib;

use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessagesFactory;
use Reliv\RcmApiLib\Api\ApiResponse\NewZfResponseFromResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Api\ApiResponse\NewZfResponseFromResponseWithTranslatedMessagesFactory;
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
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesNoMessage;
use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessagesNoMessageFactory;
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
                'factories' => [
                    /**
                     * Api ApiResponse ===================================
                     */
                    NewPsrResponseWithTranslatedMessages::class
                    => NewPsrResponseWithTranslatedMessagesFactory::class,

                    NewZfResponseFromResponseWithTranslatedMessages::class
                    => NewZfResponseFromResponseWithTranslatedMessagesFactory::class,

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
                     * Api HydrateApiMessages ===================================
                     */
                    /* MAIN HYDRATOR */
                    HydrateApiMessages::class
                    => HydrateApiMessagesCompositeFactory::class,

                    // ApiMessage
                    HydrateApiMessagesApiMessage::class
                    => HydrateApiMessagesApiMessageFactory::class,

                    // Array
                    HydrateApiMessagesArray::class
                    => HydrateApiMessagesArrayFactory::class,

                    // Exception
                    HydrateApiMessagesException::class
                    => HydrateApiMessagesExceptionFactory::class,

                    // InputFilter
                    HydrateApiMessagesInputFilter::class
                    => HydrateApiMessagesInputFilterFactory::class,

                    // Default
                    HydrateApiMessagesNoMessage::class
                    => HydrateApiMessagesNoMessageFactory::class,

                    // String
                    HydrateApiMessagesString::class
                    => HydrateApiMessagesStringFactory::class,

                    /**
                     * Api Translate ===================================
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
                    HydrateApiMessagesNoMessage::class => 100,
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
