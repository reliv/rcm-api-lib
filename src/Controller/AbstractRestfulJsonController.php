<?php

namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
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
        /** ViewHelper /RcmI18n\ViewHelper/ParamTranslate */
        return $this->paramTranslate(
            $message,
            $params,
            $textDomain,
            $locale
        );
    }

    /**
     * translateApiResponseMessages
     *
     * @return void
     */
    protected function translateApiResponseMessages()
    {
        $apiMessages = $this->response->getApiMessages();

        /** @var ApiMessage $apiMessage */
        foreach ($apiMessages as $apiMessage) {
            $apiMessage->setValue(
                $this->translateMessage(
                    $apiMessage->getValue(),
                    $apiMessage->getParams()
                )
            );
        }
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
     *
     * @return ApiResponse
     */
    protected function getApiResponse(
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $this->response->setData($data);

        if (!empty($apiMessagesData)) {
            $this->addApiMessage(
                $apiMessagesData
            );
        }

        $this->translateApiResponseMessages();

        $this->response->setStatusCode($statusCode);

        return $this->response;
    }

    /**
     * setApiMessages
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    protected function setApiMessage(
        ApiMessage $apiMessage
    ) {
        $this->response->addApiMessage($apiMessage);
    }

    /**
     * addApiMessage
     *
     * @param $apiMessagesData
     *
     * @return void
     */
    protected function addApiMessage(
        $apiMessagesData
    ) {
        $hydrator = $this->getHydrator();

        $apiMessages = $this->response->getApiMessages();

        $hydrator->hydrate($apiMessagesData, $apiMessages);
    }

    /**
     * setApiMessages
     *
     * @param ApiMessages $apiMessages
     *
     * @return void
     */
    protected function setApiMessages(
        ApiMessages $apiMessages
    ) {
        $this->response->setApiMessages($apiMessages);
    }

    /**
     * addApiMessages
     *
     * @param array|ArrayIterator $apiMessagesData
     *
     * @return void
     */
    protected function addApiMessages(
        $apiMessagesData
    ) {
        foreach ($apiMessagesData as $apiMessage) {
            $this->addApiMessage($apiMessage);
        }
    }
}
