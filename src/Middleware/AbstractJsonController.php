<?php

namespace Reliv\RcmApiLib\Middleware;

use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;
use Reliv\RcmApiLib\Model\ApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class AbstractJsonController
{
    /**
     * @var NewPsrResponseWithTranslatedMessages
     */
    protected $newPsrResponseWithTranslatedMessages;

    /**
     * @param NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
     */
    public function __construct(
        NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
    ) {
        $this->newPsrResponseWithTranslatedMessages = $newPsrResponseWithTranslatedMessages;
    }

    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param null  $apiMessageData
     * @param array $headers
     * @param array $options
     *
     * @return ApiResponseInterface|PsrApiResponse
     */
    protected function getApiResponse(
        $data,
        $statusCode = 200,
        $apiMessageData = null,
        array $headers = [],
        array $options = []
    ) {
        return $this->newPsrResponseWithTranslatedMessages->__invoke(
            $data,
            $statusCode,
            $apiMessageData,
            $headers,
            $options
        );
    }
}
