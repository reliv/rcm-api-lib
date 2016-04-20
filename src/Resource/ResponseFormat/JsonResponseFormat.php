<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * interface ResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class JsonResponseFormat extends AbstractResponseFormat
{
    /**
     * @var string
     */
    protected static $noDataModeSet = '_NO_DATA_MODEL_SET_';

    /**
     * build
     * - Set the format to the Response
     *
     * @param Request  $request
     * @param Response $response
     * @param null     $dataModel
     *
     * @return Response
     * @throws ResponseFormatException
     */
    public function build(Request $request, Response $response, $dataModel = null)
    {
        $body = $response->getBody();
        $content = json_encode($dataModel);
        $err = json_last_error();
        if ($err !== JSON_ERROR_NONE) {
            throw new ResponseFormatException('json_encode failed to encode');
        }
        $body->write($content);
        return $response->withBody($body)->withHeader('Content-Type', 'application/json');
    }

    /**
     * isValid
     * - Is this ResponseFormat valid for the request
     *
     * @param Request  $request
     * @param Response $response
     * @param mixed     $dataModel
     *
     * @return bool
     */
    public function isValid(Request $request, Response $response, $dataModel = null)
    {
        $options = $this->getOptions($request);

        $contentType = $request->getHeader('Content-Type');

        $validContentTypes = $options->get('validContentTypes', []);

        // allow this for all check
        if (in_array('*', $validContentTypes)) {
            return true;
        }

        return in_array($contentType, $validContentTypes);
    }
}
