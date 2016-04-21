<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Model\BaseResponseFormatModel;
use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
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
     * getResponseFormats
     *
     * @param Options $options
     *
     * @return array
     */
    public function getResponseFormatModels(Options $options)
    {
        $config = $options->_toArray();

        $responseFormats = [];

        foreach ($config as $serviceAlias => $formatOptions) {
            $subOptions = new GenericOptions($formatOptions);
            $responseFormats[$serviceAlias] = new BaseResponseFormatModel(
                $serviceAlias,
                $this->serviceManager->get($formatOptions['serviceName']),
                $subOptions
            );
        }

        return $responseFormats;
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
     * @throws ResponseFormatException
     */
    public function build(Request $request, Response $response, $dataModel = null)
    {
        $options = $this->getOptions($request);
        $responseFormatModels = $this->getResponseFormatModels($options);

        /** @var ResponseFormatModel $responseFormatModel */
        foreach ($responseFormatModels as $serviceAlias => $responseFormatModel) {
            $responseFormat = $responseFormatModel->getService();
            $subRequest = $request->withAttribute(
                ResponseFormatModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE_FORMAT,
                $responseFormatModel
            );

            if ($responseFormat->isValid($request, $response, $dataModel)) {
                return $responseFormat->build($subRequest, $response, $dataModel);
            }
        }

        throw new ResponseFormatException('No valid ResponseFormat found');
    }

    /**
     * isValid
     *
     * @param Request  $request
     * @param Response $response
     * @param null     $dataModel
     *
     * @return bool
     */
    public function isValid(
        Request $request,
        Response $response,
        $dataModel = null
    ) {
        $options = $this->getOptions($request);
        $responseFormatModels = $this->getResponseFormatModels($options);

        /** @var ResponseFormatModel $responseFormatModel */
        foreach ($responseFormatModels as $serviceAlias => $responseFormatModel) {

            $responseFormat = $responseFormatModel->getService();

            $subRequest = $request->withAttribute(
                ResponseFormatModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE_FORMAT,
                $responseFormatModel
            );

            if ($responseFormat->isValid($subRequest, $response, $dataModel)) {
                return true;
            }
        }

        return false;
    }
}
