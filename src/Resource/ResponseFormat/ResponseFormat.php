<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;

/**
 * interface ResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResponseFormat
{
    /**
     * build - Set the format to the Response
     *
     * @param Response $response
     * @param mixed    $apiModel
     * @param array    $options
     *
     * @return Response
     * @throws ResponseFormatException
     */
    public function build(Response $response, $apiModel, array $options = []);

    /**
     * isValid - Is this ResponseFormat valid for the request
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isValid(Request $request);
}
