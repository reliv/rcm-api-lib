<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Zend\Http\Response;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewZfResponseFromResponseWithTranslatedMessages
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
     * @param Response $zfResponse
     * @param mixed    $data
     * @param int      $statusCode
     * @param null     $apiMessageData
     * @param array    $options
     *
     * @return ApiResponseInterface|ApiResponse
     */
    public function __invoke(
        Response $zfResponse,
        $data,
        $statusCode = 200,
        $apiMessageData = null,
        array $options = []
    ) {
        if ($zfResponse instanceof ApiResponseInterface) {
            $apiResponse = $zfResponse;
        } else {
            $apiResponse = new ApiResponse();
            $apiResponse->setHeaders($zfResponse->getHeaders());
        }

        $apiResponse->setData($data);
        $apiResponse->setStatusCode($statusCode);

        if (empty($apiMessageData)) {
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
