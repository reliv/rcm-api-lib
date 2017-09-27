<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Api\Translate\OptionsTranslate;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Model\ApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithTranslatedApiMessageBasic implements WithTranslatedApiMessage
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @param Translate $translate
     */
    public function __construct(
        Translate $translate
    ) {
        $this->translate = $translate;
    }

    /**
     * @param ApiMessage $apiMessage
     * @param array      $optionsTranslate
     *
     * @return ApiMessage
     */
    public function __invoke(
        ApiMessage $apiMessage,
        array $optionsTranslate = []
    ): ApiMessage {
        $apiMessage->setValue(
            $this->translate->__invoke(
                $apiMessage->getValue(),
                $apiMessage->getParams(),
                $optionsTranslate
            )
        );

        return $apiMessage;
    }
}
