<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;
use Reliv\RcmApiLib\Service\PsrResponseService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class AbstractJsonController
{
    /**
     * @var PsrResponseService
     */
    protected $psrResponseService;

    /**
     * @param NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
     */
    public function __construct(
        NewPsrResponseWithTranslatedMessages $newPsrResponseWithTranslatedMessages
    ) {
        $this->newPsrResponseWithTranslatedMessages = $newPsrResponseWithTranslatedMessages;
    }

    /**
     * @param mixed             $data
     * @param int               $statusCode
     * @param null              $apiMessagesData
     * @param array             $headers
     * @param array             $options
     *
     * @return ApiResponseInterface|PsrApiResponse
     */
    protected function getApiResponse(
        $data,
        $statusCode = 200,
        $apiMessagesData = null,
        array $headers = [],
        array $options = []
    ) {
        return $this->newPsrResponseWithTranslatedMessages->__invoke(
            $data,
            $statusCode,
            $apiMessagesData,
            $headers,
            $options
        );
    }
}
