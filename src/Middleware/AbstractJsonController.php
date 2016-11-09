<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;
use Reliv\RcmApiLib\Service\PsrApiResponseBuilder;
use Reliv\RcmApiLib\Service\ResponseService;

/**
 * Class AbstractJsonController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AbstractJsonController
{
    /**
     * @var ResponseService
     */
    protected $responseService;

    /**
     * @var PsrApiResponseBuilder
     */
    protected $psrApiResponseBuilder;

    /**
     * AbstractJsonController constructor.
     *
     * @param ResponseService       $responseService
     * @param PsrApiResponseBuilder $psrApiResponseBuilder
     */
    public function __construct(
        ResponseService $responseService,
        PsrApiResponseBuilder $psrApiResponseBuilder
    ) {
        $this->responseService = $responseService;
        $this->psrApiResponseBuilder = $psrApiResponseBuilder;
    }

    /**
     * getApiResponse
     *
     * @param ResponseInterface $response
     * @param mixed             $data
     * @param int               $statusCode
     * @param null              $apiMessagesData
     *
     * @return PsrApiResponse|ApiResponseInterface
     */
    protected function getApiResponse(
        ResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $response = $this->psrApiResponseBuilder->__invoke($response);

        return $this->responseService->getApiResponse(
            $response,
            $data,
            $statusCode,
            $apiMessagesData
        );
    }
}
