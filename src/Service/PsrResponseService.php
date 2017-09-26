<?php

namespace Reliv\RcmApiLib\Service;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * @deprecated Use \Reliv\RcmApiLib\Api\ApiResponse
 */
class PsrResponseService extends ResponseService
{
    /**
     * @deprecated Use NewPsrResponseWithTranslatedMessages
     * getApiResponse
     *
     * @param ResponseInterface $response
     * @param mixed             $data
     * @param int               $statusCode
     * @param null              $apiMessagesData
     *
     * @return PsrApiResponse|ApiResponseInterface
     */
    public function getPsrApiResponse(
        ResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $apiResponse = new PsrApiResponse(
            null,
            [],
            $response->getStatusCode(),
            $response->getHeaders(),
            0
        );

        return $this->getApiResponse(
            $apiResponse,
            $data,
            $statusCode,
            $apiMessagesData
        );
    }
}
