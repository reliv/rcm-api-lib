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
     * @param mixed                $apiMessageData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        $apiMessageData
    ): ApiResponseInterface;
}
