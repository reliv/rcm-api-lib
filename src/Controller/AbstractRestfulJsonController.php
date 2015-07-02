<?php


namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\ApiResponse;
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
     * translateMessage
     *
     * @todo Make Plugin
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

        if (!$response) {
            $response = new ApiResponse();
        }

        if (!$response instanceof ApiResponse) {
            throw new \InvalidArgumentException(
                'Expected an ApiResponse'
            );
        }

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
        return $this->getMessageResponse(
            [
                'key' => 'Method_Not_Allowed',
                'message' => 'Method Not Allowed',
                'type' => ApiResponse::PRIMARY_TYPE,
                'code' => 'Http_Method_Not_Allowed'
            ],
            $params = [],
            $hydrator = 'Array',
            $statusCode = 405
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
     * getMessageResponse
     *
     * @param        $data
     * @param string $hydrator
     * @param array  $params
     * @param int    $statusCode
     *
     * @return ApiResponse
     */
    protected function getMessageResponse(
        $data,
        $hydrator = 'ApiMessage',
        $params = [],
        $statusCode = 400
    ) {
        $hydratorMethod = 'hydrateResponseMessage' . $hydrator;

        if (method_exists($this, $hydratorMethod)) {
            $apiMessages = $this->$hydratorMethod(
                $data,
                $params
            );
            $this->response->setApiMessages($apiMessages);
        }

        $this->response->setStatusCode($statusCode);

        return $this->response;
    }

    /**
     * hydrateResponseMessageApiMessage
     *
     * @todo Abstract hydrators
     *
     * @param ApiMessage $data
     * @param array      $params
     *
     * @return array of ApiMessage
     */
    protected function hydrateResponseMessageApiMessage(
        ApiMessage $data,
        $params = []
    ) {
        $apiMessage = $data;
        if (!empty($params)) {
            $apiMessage->setParams($params);
        }

        $apiMessage->setValue(
            $this->translateMessage(
                $apiMessage->getValue(),
                $apiMessage->getParams()
            )
        );

        return [$apiMessage];
    }

    /**
     * hydrateResponseMessageArray
     *
     * @todo Abstract hydrators
     *
     * @param array $data
     * @param array $params
     *
     * @return array of ApiMessage
     */
    protected function hydrateResponseMessageArray(
        array $data,
        $params = []
    ) {
        $default = [
            'type' => ApiResponse::PRIMARY_TYPE,
            'code' => null,
            'params' => $params
        ];

        $data = array_merge($default, $data);

        $apiMessage = new ApiMessage(
            $data['key']
        );

        $apiMessage->populate($data);

        $apiMessage->setValue(
            $this->translateMessage(
                $apiMessage->getValue(),
                $apiMessage->getParams()
            )
        );

        return [$apiMessage];
    }

    /**
     * hydrateResponseMessageInputFilterMessages
     *
     * @todo Abstract hydrators
     *
     * @param array $inputFilterMessages
     * @param array $params
     *
     * @return array of ApiMessage
     */
    protected function hydrateResponseMessageInputFilterMessages(
        $inputFilterMessages,
        $params = []
    ) {
        $type = 'field';

        $apiMessages = [];

        foreach ($inputFilterMessages as $key => $message) {
            foreach ($message as $fkey => $fmessage) {
                $apiMessage = new ApiMessage(
                    $key,
                    $fmessage,
                    $type,
                    $fkey,
                    $params
                );

                $apiMessage->setValue(
                    $this->translateMessage(
                        $apiMessage->getValue(),
                        $apiMessage->getParams()
                    )
                );

                $apiMessages[] = $apiMessage;
            }
        }

        return $apiMessages;
    }

    /**
     * hydrateResponseMessageException
     *
     * @todo Abstract hydrators
     *
     * @param \Exception $exception
     * @param array      $params
     *
     * @return array of ApiMessage
     */
    protected function hydrateResponseMessageException(
        \Exception $exception,
        $params = []
    ) {
        $type = 'exception';

        if(method_exists($exception, 'getParms')){
            // @todo this should be in its own hydrator
            $params = array_merge($params, $exception->getParms());
        }

        $apiMessage = new ApiMessage(
            get_class($exception),
            $exception->getMessage(),
            $type,
            $exception->getCode(),
            $params
        );

        $apiMessage->setValue(
            $this->translateMessage(
                $apiMessage->getValue(),
                $apiMessage->getParams()
            )
        );

        return [$apiMessage];
    }
}
