<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Model\ApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithTranslatedApiMessagesBasic implements WithTranslatedApiMessages
{
    /**
     * @var WithTranslatedApiMessage
     */
    protected $withTranslatedApiMessage;

    /**
     * @param WithTranslatedApiMessage $withTranslatedApiMessage
     */
    public function __construct(
        WithTranslatedApiMessage $withTranslatedApiMessage
    ) {
        $this->withTranslatedApiMessage = $withTranslatedApiMessage;
    }

    /**
     * @param ApiResponseInterface $apiResponse
     * @param array                $optionsTranslate
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        array $optionsTranslate = []
    ): ApiResponseInterface {
        $apiMessages = $apiResponse->getApiMessages();

        /** @var ApiMessage $apiMessage */
        foreach ($apiMessages as $apiMessage) {
            // @todo This should use the return and be immutable
            $this->withTranslatedApiMessage->__invoke(
                $apiMessage,
                $optionsTranslate
            );
        }

        return $apiResponse;
    }
}
