<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Options\Options;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZfCompositeResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfCompositeResponseFormat extends AbstractResponseFormat
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;
    /**
     * @var array
     */
    protected $responseFormats = [];

    /**
     * ZfCompositeResponseFormat constructor.
     *
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        ServiceLocatorInterface $serviceManager
    ) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * add
     *
     * @param ResponseFormat $responseFormat
     *
     * @return void
     */
    public function add($serviceAlias, ResponseFormat $responseFormat)
    {
        $this->responseFormats[$serviceAlias] = $responseFormat;
    }

    /**
     * addByName
     *
     * @param string $serviceAlias
     * @param string $serviceName
     *
     * @return void
     */
    public function addByName($serviceAlias, $serviceName)
    {
        if ($this->hasService($serviceAlias)) {
            return;
        }

        /** @var ResponseFormat $responseFormat */
        $responseFormat = $this->serviceManager->get($serviceName);

        $this->add($serviceAlias, $responseFormat);
    }

    /**
     * hasService
     *
     * @param string $serviceAlias
     *
     * @return mixed
     */
    public function hasService($serviceAlias)
    {
        return array_key_exists($serviceAlias, $this->responseFormats);
    }

    /**
     * getResponseFormats
     *
     * @param Options $options
     *
     * @return array
     */
    public function getResponseFormats(Options $options)
    {
        $config = $options->_toArray();

        foreach ($config as $serviceAlias => $formatOptions) {
            $this->addByName($serviceAlias, $formatOptions['serviceName']);
        }

        return $this->responseFormats;
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
        $responseFormats = $this->getResponseFormats($options);

        /** @var ResponseFormat $responseFormat */
        foreach ($responseFormats as $serviceAlias => $responseFormat) {
            if ($responseFormat->isValid($request, $response, $options, $dataModel)) {
                $subOptions = $options->getOptions($serviceAlias);

                return $responseFormat->build($request, $response, $dataModel, $subOptions);
            }
        }

        throw new ResponseFormatException('No valid ResponseFormat found');
    }

    /**
     * isValid
     *
     * @param Request  $request
     * @param Response $response
     * @param Options  $options
     * @param null     $dataModel
     *
     * @return bool
     */
    public function isValid(Request $request, Response $response, Options $options, $dataModel = null)
    {
        $responseFormats = $this->getResponseFormats($options);
        
        /** @var ResponseFormat $responseFormat */
        foreach ($responseFormats as $serviceAlias => $responseFormat) {
            $subOptions = $options->getOptions($serviceAlias);
            if ($responseFormat->isValid($request, $response, $subOptions, $dataModel)) {
                return true;
            }
        }

        return false;
    }
}
