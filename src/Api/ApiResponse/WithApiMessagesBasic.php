<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithApiMessagesBasic implements WithApiMessages
{
    /**
     * @var WithApiMessage
     */
    protected $withApiMessage;

    /**
     * @param WithApiMessage $withApiMessage
     */
    public function __construct(
        WithApiMessage $withApiMessage
    ) {
        $this->withApiMessage = $withApiMessage;
    }

    /**
     * @param ApiResponseInterface $apiResponse
     * @param array|\Traversable   $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        $apiMessagesData
    ): ApiResponseInterface
    {
        foreach ($apiMessagesData as $apiMessageData) {
            $apiResponse = $this->withApiMessage->__invoke($apiResponse, $apiMessageData);
        }

        return $apiResponse;
    }
}
