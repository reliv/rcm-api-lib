<?php

namespace Reliv\RcmApiLib\Service;

use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * Class PsrApiResponseBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class PsrApiResponseBuilder
{
    /**
     * build
     *
     * @param ResponseInterface $response
     * @param null              $data
     * @param array             $apiMessages
     * @param int               $encodingOptions
     *
     * @return PsrApiResponse
     */
    public static function build(
        ResponseInterface $response,
        $data = null,
        $apiMessages = [],
        $encodingOptions = 0
    ) {
        return new PsrApiResponse(
            $data,
            $apiMessages,
            $response->getStatusCode(),
            $response->getHeaders(),
            $encodingOptions
        );
    }

    /**
     * __invoke
     *
     * @param ResponseInterface $response
     * @param null              $data
     * @param array             $apiMessages
     * @param int               $encodingOptions
     *
     * @return PsrApiResponse
     */
    public function __invoke(
        ResponseInterface $response,
        $data = null,
        $apiMessages = [],
        $encodingOptions = 0
    ) {
        return self::build(
            $response,
            $data,
            $apiMessages,
            $encodingOptions
        );
    }
}
