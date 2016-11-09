<?php

namespace Reliv\RcmApiLib\Service;

use Psr\Http\Message\ResponseInterface;
use RcmI18n\Service\ParameterizeTranslator;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * Class AbstractJsonController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class PsrResponseService extends ResponseService
{
    /**
     * @var PsrApiResponseBuilder
     */
    protected $psrApiResponseBuilder;

    /**
     * PsrResponseService constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     * @param ParameterizeTranslator                $parameterizeTranslator
     * @param PsrApiResponseBuilder                 $psrApiResponseBuilder
     */
    public function __construct(
        $container,
        ParameterizeTranslator $parameterizeTranslator,
        PsrApiResponseBuilder $psrApiResponseBuilder
    ) {
        $this->psrApiResponseBuilder = $psrApiResponseBuilder;
        parent::__construct(
            $container,
            $parameterizeTranslator
        );
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
    public function getPsrApiResponse(
        ResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $response = $this->psrApiResponseBuilder->__invoke($response);

        return $this->getApiResponse(
            $response,
            $data,
            $statusCode,
            $apiMessagesData
        );
    }
}
