<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\RuntimeOptions;

/**
 * interface ResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResponseFormat extends RuntimeOptions
{
    /**
     * DATA_MODEL_KEY
     */
    const DATA_MODEL_KEY = 'resource-response-data-model';

    /**
     * build
     * - Set the format to the Response
     *
     * @param Request  $request
     * @param Response $response
     * @param mixed    $dataModel
     * @param array    $options
     *
     * @return Response
     */
    public function build(Request $request, Response $response, $dataModel = null, array $options = []);

    /**
     * isValid
     * - Is this ResponseFormat valid for the request
     *
     * @param Request  $request
     * @param Response $response
     * @param mixed    $dataModel
     * @param array    $options
     *
     * @return bool
     */
    public function isValid(Request $request, Response $response, $dataModel = null, array $options = []);
}
