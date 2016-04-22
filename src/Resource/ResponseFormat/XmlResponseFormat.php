<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Model\ApiSerializableInterface;

/**
 * interface XmlResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class XmlResponseFormat extends AbstractResponseFormat implements ResponseFormat
{
    /**
     * arrayToXml
     *
     * @param array             $data
     * @param \SimpleXMLElement $xml_data
     *
     * @return void
     */
    protected function arrayToXml(array $data, \SimpleXMLElement &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                //dealing with <0/>..<n/> issues
                if (is_numeric($key)) {
                    $key = 'item' . $key;
                }
                $subNode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subNode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * build
     * - Set the format to the Response
     *
     * @param Request  $request
     * @param Response $response
     * @param null     $dataModel
     *
     * @return Response
     */
    public function build(Request $request, Response $response, $dataModel = null)
    {
        $options = $this->getOptions($request);

        $body = $response->getBody();

        $xmlData = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');

        $content = null;

        if (is_array($dataModel)) {
            $this->arrayToXml($dataModel, $xmlData);

            $content = $xmlData->asXML();
        }

        if ($dataModel instanceof ApiSerializableInterface) {
            $this->arrayToXml($dataModel->toArray(), $xmlData);

            $content = $xmlData->asXML();
        }

        $body->write($content);

        return $response->withBody($body)->withHeader('Content-Type', 'application/xml');
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

        $validContentTypes = $options->get('validContentTypes', []);

        // allow this for all check
        if (in_array('*/*', $validContentTypes)) {
            return true;
        }

        $contentTypes = $request->getHeader('Accept');

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType, $validContentTypes)) {
                return true;
            }
        }

        return false;
    }
}
