<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewZfResponseWithTranslatedMessages
{
    /**
     * @var WithApiMessage
     */
    protected $withApiMessage;
    /**
     * @var WithTranslatedApiMessages
     */
    protected $withTranslatedApiMessages;

    /**
     * @param WithApiMessage            $withApiMessage
     * @param WithTranslatedApiMessages $withTranslatedApiMessages
     */
    public function __construct(
        WithApiMessage $withApiMessage,
        WithTranslatedApiMessages $withTranslatedApiMessages
    ) {
        $this->withApiMessage = $withApiMessage;
        $this->withTranslatedApiMessages = $withTranslatedApiMessages;
    }

    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param null  $apiMessageData
     * @param array $headers
     * @param array $options
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponseInterface|PsrApiResponse
     */
    public function __invoke(
        $data,
        $statusCode = 200,
        $apiMessageData = null,
        $headers = [],
        array $options = []
    ) {
        $apiResponse = new ApiResponse();

        if (!empty($apiMessagesData)) {
            return $apiResponse;
        }

        $this->withApiMessage->__invoke(
            $apiResponse,
            $apiMessageData
        );

        $apiResponse = $this->withTranslatedApiMessages->__invoke(
            $apiResponse
        );

        return $apiResponse;
    }
}
