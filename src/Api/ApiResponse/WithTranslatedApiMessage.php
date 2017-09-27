<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Model\ApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface WithTranslatedApiMessage
{
    /**
     * @param ApiMessage $apiMessage
     * @param array      $optionsTranslate
     *
     * @return ApiMessage
     */
    public function __invoke(
        ApiMessage $apiMessage,
        array $optionsTranslate = []
    ): ApiMessage;
}
