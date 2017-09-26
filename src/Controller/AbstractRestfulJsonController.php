<?php

namespace Reliv\RcmApiLib\Controller;

use Interop\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\ApiResponse\NewZfResponseFromResponseWithTranslatedMessages;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessage;
use Reliv\RcmApiLib\Api\ApiResponse\WithApiMessages;
use Reliv\RcmApiLib\Api\ApiResponse\WithTranslatedApiMessages;
use Reliv\RcmApiLib\Api\Translate\OptionsTranslate;
use Reliv\RcmApiLib\Api\Translate\Translate;
use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorInterface;
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
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator = null
    ) {
        if ($serviceLocator) {
            $this->serviceLocator = $serviceLocator;
        }
    }

    /**
     * translateMessage
     *
     * @param        $message
     * @param array  $params
     * @param string $textDomain
     * @param null   $locale
     *
     * @return string
     */
    protected function translateMessage(
        $message,
        $params = [],
        $textDomain = 'default',
        $locale = null
    ) {
        /** @var Translate $translate */
        $translate = $this->serviceLocator->get(
            Translate::class
        );

        return $translate->__invoke(
            $message,
            $params,
            [
                OptionsTranslate::OPTIONS_TEXT_DOMAIN => $textDomain,
                OptionsTranslate::OPTIONS_LOCALE => $locale,
            ]
        );
    }

    /**
     * @param array $optionsTranslate
     *
     * @return ApiResponse|ApiResponseInterface
     */
    protected function translateApiResponseMessages(
        array $optionsTranslate = []
    ) {
        /** @var WithTranslatedApiMessages $withTranslatedApiMessages */
        $withTranslatedApiMessages = $this->serviceLocator->get(
            WithTranslatedApiMessages::class
        );

        return $withTranslatedApiMessages->__invoke(
            $this->getResponse(),
            $optionsTranslate
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
     * @return ApiResponse|ApiResponseInterface
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

        /** @var NewZfResponseFromResponseWithTranslatedMessages $newZfResponseFromResponseWithTranslatedMessages */
        $newZfResponseFromResponseWithTranslatedMessages = $this->serviceLocator->get(
            NewZfResponseFromResponseWithTranslatedMessages::class
        );

        return $newZfResponseFromResponseWithTranslatedMessages->__invoke(
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
        /** @var NewZfResponseFromResponseWithTranslatedMessages $newZfResponseFromResponseWithTranslatedMessages */
        $newZfResponseFromResponseWithTranslatedMessages = $this->serviceLocator->get(
            NewZfResponseFromResponseWithTranslatedMessages::class
        );

        return $newZfResponseFromResponseWithTranslatedMessages->__invoke(
            $this->getResponse(),
            $data,
            $statusCode,
            $apiMessagesData
        );
    }

    /**
     * @param ApiMessage $apiMessage
     *
     * @return ApiResponse|ApiResponseInterface
     */
    protected function setApiMessage(
        ApiMessage $apiMessage
    ) {
        $apiResponse = $this->getResponse();
        $apiResponse->addApiMessage($apiMessage);

        return $apiResponse;
    }

    /**
     * @param $apiMessageData
     *
     * @return ApiResponse|ApiResponseInterface
     */
    protected function addApiMessage(
        $apiMessageData
    ) {
        /** @var WithApiMessage $withApiMessage */
        $withApiMessage = $this->serviceLocator->get(
            WithApiMessage::class
        );

        return $withApiMessage->__invoke(
            $this->getResponse(),
            $apiMessageData
        );
    }

    /**
     * @param ApiMessages $apiMessages
     *
     * @return ApiResponse|ApiResponseInterface
     */
    protected function setApiMessages(
        ApiMessages $apiMessages
    ) {
        $apiResponse = $this->getResponse();

        $apiResponse->setApiMessages($apiMessages);

        return $apiResponse;
    }

    /**
     * @param array|\Traversable $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    protected function addApiMessages(
        $apiMessagesData
    ) {
        /** @var WithApiMessages $withApiMessages */
        $withApiMessages = $this->serviceLocator->get(
            WithApiMessages::class
        );

        return $withApiMessages->__invoke(
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
        return $this->getResponse()->getApiMessages();
    }

    /**
     * hasApiMessages
     *
     * @return bool
     */
    protected function hasApiMessages()
    {
        $apiMessages = $this->getResponse()->getApiMessages();

        return $apiMessages->has();
    }
}
