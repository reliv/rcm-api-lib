<?php


namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Model\ApiMessage;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Class AbstractRestfulJsonController
 *
 * ZF2 AbstractRestfulController returns arrays for missing methods
 * This allows proper responses to be returned
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractRestfulJsonController extends AbstractRestfulController
{

    /**
     * @override
     * @var ApiResponse
     */
    protected $response;

    protected $hydrator;

    /**
     * getTranslator
     *
     * @return \Zend\I18n\Translator\TranslatorInterface.
     */
    protected function getTranslator()
    {
        return $this->serviceLocator->get('MvcTranslator');
    }

    /**
     * getHydrator
     *
     * @return \Reliv\RcmApiLib\Hydrator\ApiMessagesHydratorInterface
     */
    protected function getHydrator()
    {
        return $this->serviceLocator->get('Reliv\RcmApiLib\Hydrator');
    }

    /**
     * translateMessage
     *
     * @todo Make Plugin - Service
     *
     * @param        $message
     * @param array  $params
     * @param string $textDomain
     * @param null   $locale
     *
     * @return mixed
     */
    protected function translateMessage(
        $message,
        $params = [],
        $textDomain = 'default',
        $locale = null
    ) {
        $translator = $this->getTranslator();

        $message = $translator->translate(
            $message,
            $textDomain,
            $locale
        );
        $openTag = '{';
        $closeTag = '}';

        foreach ($params as $name => $value) {
            $message = str_replace(
                $openTag . $name . $closeTag,
                $value,
                $message
            );
        }

        return $message;
    }

    /**
     * @override
     * dispatch
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return mixed|ResponseInterface
     */
    public function dispatch(
        RequestInterface $request,
        ResponseInterface $response = null
    ) {
        $this->request = $request;

        // Overrides any other response
        $response = new ApiResponse();

        $this->response = $response;

        return parent::dispatch($request, $response);
    }

    /**
     * methodNotAllowed
     *
     * @return ApiResponse
     */
    protected function methodNotAllowed()
    {
        $apiMessage = new ApiMessage(
            'Http',
            'Method Not Allowed',
            'Method_Not_Allowed',
            '405',
            true
        );

        return $this->getApiResponse(
            null,
            405,
            $apiMessage
        );
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $data
     *
     * @return ApiResponse
     */
    public function create($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     *
     * @return ApiResponse
     */
    public function delete($id)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $data
     *
     * @return ApiResponse
     */
    public function deleteList($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param $id
     *
     * @return ApiResponse
     */
    public function get($id)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @return ApiResponse
     */
    public function getList()
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions as they do not return valid JsonModels
     *
     * @param null $id
     *
     * @return ApiResponse
     */
    public function head($id = null)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions
     *
     * @return ApiResponse
     */
    public function options()
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions
     *
     * @param $id
     * @param $data
     *
     * @return ApiResponse
     */
    public function patch($id, $data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions
     *
     * @param $data
     *
     * @return ApiResponse
     */
    public function replaceList($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions
     *
     * @param $data
     *
     * @return ApiResponse
     */
    public function patchList($data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * Override default actions
     *
     * @param $id
     * @param $data
     *
     * @return ApiResponse
     */
    public function update($id, $data)
    {
        return $this->methodNotAllowed();
    }

    /**
     * getApiResponse
     *
     * @param      $data
     * @param int  $statusCode
     * @param null $apiMessagesData
     * @param null $apiMessagesIsPrimary
     *
     * @return ApiResponse
     */
    protected function getApiResponse(
        $data,
        $statusCode = 200,
        $apiMessagesData = null,
        $apiMessagesIsPrimary = null
    ) {
        $this->response->setData($data);

        if (!empty($apiMessagesData)) {
            $this->addApiMessage(
                $apiMessagesData,
                $apiMessagesIsPrimary
            );
        }

        $this->response->setStatusCode($statusCode);

        return $this->response;
    }

    /**
     * addApiMessage
     *
     * @param mixed $apiMessagesData
     * @param null  $apiMessagesIsPrimary
     *
     * @return void
     */
    protected function addApiMessage(
        $apiMessagesData,
        $apiMessagesIsPrimary = null
    ) {
        $hydrator = $this->getHydrator();

        $apiMessages = $this->response->getApiMessages();

        $apiMessages = $hydrator->hydrate($apiMessagesData, $apiMessages);

        /** @var ApiMessage $apiMessage */
        foreach ($apiMessages as $apiMessage) {
            if ($apiMessage->getPrimary() === null) {
                $apiMessage->setPrimary($apiMessagesIsPrimary);
            }

            $apiMessage->setValue(
                $this->translateMessage(
                    $apiMessage->getValue(),
                    $apiMessage->getParams()
                )
            );
        }
    }

    /**
     * addApiMessages
     *
     * @param array $apiMessagesData
     * @param null  $apiMessagesIsPrimary
     *
     * @return void
     */
    protected function addApiMessages(
        array $apiMessagesData,
        $apiMessagesIsPrimary = null
    ) {
        foreach ($apiMessagesData as $apiMessage) {
            $this->addApiMessage($apiMessage, $apiMessagesIsPrimary);
        }
    }
}
