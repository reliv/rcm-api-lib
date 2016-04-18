<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class CompositeResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class CompositeResponseFormat extends AbstractResponseFormat
{
    /**
     * @var array
     */
    protected $responseFormats = [];

    /**
     * add
     *
     * @param ResponseFormat $responseFormat
     *
     * @return void
     */
    public function add(ResponseFormat $responseFormat)
    {
        $this->responseFormats[] = $responseFormat;
    }

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
     * @throws ResponseFormatException
     */
    public function build(Request $request, Response $response, Options $options, $dataModel = null)
    {
        /** @var ResponseFormat $responseFormat */
        foreach ($this->responseFormats as $responseFormat) {
            if($responseFormat->isValid($request, $response, $dataModel, $options)) {
                return $responseFormat->build($request, $response, $dataModel, $options);
            }
        }

        throw new ResponseFormatException('No valid ResponseFormat found');
    }

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
    public function isValid(Request $request, Response $response, $dataModel = null, array $options = [])
    {
        /** @var ResponseFormat $responseFormat */
        foreach ($this->responseFormats as $responseFormat) {
            if($responseFormat->isValid($request, $response, $dataModel, $options)) {
                return true;
            }
        }

        return false;
    }
}
