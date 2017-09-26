<?php

namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Service\ResponseService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class AbstractRestfulJsonController extends AbstractRestfulController
{
    /**
     * @override
     * @var ApiResponse
     */
    protected $response;

    /**
     * getResponseService
     *
     * @return ResponseService
     */
    protected function getResponseService()
    {
        return $this->serviceLocator->get(ResponseService::class);
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
        return $this->getResponseService()->translateMessage(
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
        $this->getResponseService()->translateApiResponseMessages(
            $this->getResponse()
        );
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

        $response = $this->getResponse();

        return parent::dispatch($request, $response);
    }

    /**
     * Get response object of type ApiResponseInterface
     *
     * @return ApiResponseInterface
     */
    public function getResponse()
    {
        // Overrides any other response
        if (!($this->response instanceof ApiResponseInterface)) {
            $this->response = new ApiResponse();
        }

        return $this->response;
    }

    /**
     * methodNotAllowed
     *
     * @return ApiResponse|ApiResponseInterface
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

        return $this->getResponseService()->getApiResponse(
            $this->getResponse(),
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
     * @return ApiResponse|ApiResponseInterface
     */
    protected function getApiResponse(
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        return $this->getResponseService()->getApiResponse(
            $this->getResponse(),
            $data,
            $statusCode,
            $apiMessagesData
        );
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
        $this->getResponseService()->setApiMessage(
            $this->getResponse(),
            $apiMessage
        );
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
        $this->getResponseService()->addApiMessage(
            $this->getResponse(),
            $apiMessagesData
        );
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
        $this->getResponseService()->setApiMessages(
            $this->getResponse(),
            $apiMessages
        );
    }

    /**
     * addApiMessages
     *
     * @param array|\ArrayIterator $apiMessagesData
     *
     * @return void
     */
    protected function addApiMessages(
        $apiMessagesData
    ) {
        $this->getResponseService()->addApiMessages(
            $this->getResponse(),
            $apiMessagesData
        );
    }

    /**
     * getApiMessages
     *
     * @return ApiMessages
     */
    protected function getApiMessages()
    {
        return $this->getResponseService()->getApiMessages(
            $this->getResponse()
        );
    }

    /**
     * hasApiMessages
     *
     * @return bool
     */
    protected function hasApiMessages()
    {
        return $this->getResponseService()->hasApiMessages(
            $this->getResponse()
        );
    }
}
