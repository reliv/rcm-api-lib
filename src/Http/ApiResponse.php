<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Http\Headers;
use Zend\Http\Response as HttpResponse;

/**
 * Class ApiResponse
 *
 * JSON ApiResponse
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiResponse extends HttpResponse implements ApiResponseInterface
{
    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var ApiMessages
     */
    protected $messages;

    /**
     * __construct
     */
    public function __construct()
    {
        /** @var Headers $headers */
        $headers = $this->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        $this->messages = new ApiMessages();
    }

    /**
     * setDataOject
     *
     * @param \JsonSerializable $data
     *
     * @return void
     */
    public function setDataOject(\JsonSerializable $data)
    {
        $this->data = $data;
    }

    /**
     * setData
     *
     * @param array|null $data
     *
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * getData
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * setContent
     *
     * @param mixed $content
     *
     * @return void
     * @throws \Exception
     */
    public function setContent($content)
    {
        throw new \Exception(
            'Content cannot be set directly, use setData, or addMssages'
        );
    }

    /**
     * getContent
     *
     * @return string
     */
    public function getContent()
    {
        $content = [
            'data' => $this->getData(),
            'messages' => $this->getApiMessages(),
        ];

        return json_encode($content);
    }

    /**
     * addApiMessages
     *
     * @param array $apiMessages ApiMessage
     *
     * @return void
     */
    public function addApiMessages($apiMessages = [])
    {
        foreach ($apiMessages as $apiMessage) {
            $this->addApiMessage($apiMessage);
        }
    }

    /**
     * getApiMessages
     *
     * @return ApiMessages
     */
    public function getApiMessages()
    {
        return $this->messages;
    }

    /**
     * addApiMessage
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    public function addApiMessage(ApiMessage $apiMessage)
    {
        $this->messages->add($apiMessage);
    }
}
