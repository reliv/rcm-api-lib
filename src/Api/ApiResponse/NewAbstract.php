<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class NewAbstract
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
     * getApiResponse
     *
     * @param ApiResponseInterface $apiResponse
     * @param                      $data
     * @param int                  $statusCode
     * @param null                 $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    protected function getApiResponse(
        ApiResponseInterface $apiResponse,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $apiResponse->setData($data);

        if (!empty($apiMessagesData)) {
            $this->withApiMessage->__invoke(
                $apiResponse,
                $apiMessagesData
            );
        }

        $apiResponse = $this->withTranslatedApiMessages->__invoke(
            $apiResponse
        );

        return $apiResponse->withStatus($statusCode);
    }
}
