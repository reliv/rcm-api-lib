<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\Options;

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
     * REQUEST_ATTRIBUTE
     */
    const REQUEST_ATTRIBUTE = 'api-lib-resource-response-data-model';

    /**
     * build
     * - Set the format to the Response
     *
     * @param Request  $request
     * @param Response $response
     * @param Options  $options
     * @param null     $dataModel
     *
     * @return Response
     */
    public function build(Request $request, Response $response, Options $options, $dataModel = null);

    /**
     * isValid
     * - Is this ResponseFormat valid for the request
     *
     * @param Request  $request
     * @param Response $response
     * @param Options  $options
     * @param mixed     $dataModel
     *
     * @return bool
     */
    public function isValid(Request $request, Response $response, Options $options, $dataModel = null);
}
