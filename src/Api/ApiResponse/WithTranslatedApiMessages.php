<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithTranslatedApiMessages
{
    /**
     * @param ApiResponseInterface $apiResponse
     * @param array                $optionsTranslate
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        array $optionsTranslate = []
    ): ApiResponseInterface;
}
