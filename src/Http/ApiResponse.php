<?php
namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
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
class ApiResponse extends HttpResponse
{
    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * __construct
     */
    public function __construct()
    {
        /** @var Headers $headers */
        $headers = $this->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');
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
     * setApiMessages
     *
     * @param array $apiMessages ApiMessage
     *
     * @return void
     */
    public function setApiMessages($apiMessages = [])
    {
        foreach ($apiMessages as $apiMessage) {
            $this->setApiMessage($apiMessage);
        }
    }

    /**
     * getApiMessages
     *
     * @return array
     */
    public function getApiMessages()
    {
        return $this->messages;
    }

    /**
     * setApiMessage
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    public function setApiMessage(ApiMessage $apiMessage)
    {
        if ($apiMessage->isPrimary()) {
            array_unshift($this->messages, $apiMessage);

            return;
        }

        $this->messages[] = $apiMessage;
    }

    /**
     * getApiMessage
     *
     * @param string $key
     * @param null   $default
     *
     * @return null|ApiMessage
     */
    public function getApiMessage($key, $default = null)
    {
        $key = (string)$key;
        /** @var ApiMessage $apiMessage */
        foreach ($this->messages as $apiMessage) {
            if ($apiMessage->getKey() === $key) {
                return $apiMessage;
            }
        }

        return $default;
    }
}
