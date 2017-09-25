<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithApiMessage
{
    /**
     * @param ApiResponseInterface $apiResponse
     * @param mixed                $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        $apiMessagesData
    ): ApiResponseInterface;
}
