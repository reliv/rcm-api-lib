<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Model\ApiSerializableInterface;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Options\DefaultResponseFormatOptions;
use Zend\Form\Annotation\Options;

/**
 * interface XmlResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class XmlResponseFormat extends AbstractResponseFormat
{
    /**
     * @var string
     */
    protected static $noDataModeSet = '_NO_DATA_MODEL_SET_';

    /**
     * JsonResponseFormat constructor.
     *
     * @param DefaultResponseFormatOptions $defaultResponseFormatOptions
     */
    public function __construct(DefaultResponseFormatOptions $defaultResponseFormatOptions)
    {
        $defaultOptions = $defaultResponseFormatOptions->getOptions(
            'Reliv\RcmApiLib\Resource\ResponseFormat\XmlResponseFormat'
        );
        parent::__construct($defaultOptions);
    }

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
     *
     * @param Request  $request
     * @param Response $response
     * @param mixed    $dataModel
     * @param array    $options
     *
     * @return Response
     * @throws ResponseFormatException
     */
    public function build(Request $request, Response $response, $dataModel = null, array $options = [])
    {
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
        $response->withBody($body);

        return $response;
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
        $options = $this->buildRuntimeOptions($options);

        $contentType = $request->getHeader('Content-Type');

        $validContentTypes = $options->get('validContentTypes', []);

        // allow this for all check
        if (in_array('*', $validContentTypes)) {
            return true;
        }

        return in_array($contentType, $validContentTypes);
    }
}
