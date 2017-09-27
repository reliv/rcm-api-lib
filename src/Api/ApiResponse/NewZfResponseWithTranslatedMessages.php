<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Zend\Http\Headers;

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
     * @return ApiResponseInterface|ApiResponse
     */
    public function __invoke(
        $data,
        $statusCode = 200,
        $apiMessageData = null,
        $headers = [],
        array $options = []
    ) {
        $apiResponse = new ApiResponse();
        $apiResponse->setData($data);
        $apiResponse->setStatusCode($statusCode);
        $headers = new Headers();
        $headers->addHeaders($headers);
        $apiResponse->setHeaders($headers);

        if (empty($apiMessagesData)) {
            return $this->withTranslatedApiMessages->__invoke(
                $apiResponse
            );
        }

        $this->withApiMessage->__invoke(
            $apiResponse,
            $apiMessageData
        );

        return $this->withTranslatedApiMessages->__invoke(
            $apiResponse
        );
    }
}
