<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class BasicApiResponse
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class BasicApiResponse implements ApiResponseInterface
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
     * BasicApiResponse constructor.
     *
     * @param null  $data
     * @param array $apiMessages
     */
    public function __construct(
        $data = null,
        $apiMessages = []
    )
    {
        $this->messages = new ApiMessages();
        $this->setData($data);
        $this->addApiMessages($apiMessages);
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
     * setApiMessages
     *
     * @param ApiMessages $apiMessages
     *
     * @return void
     */
    public function setApiMessages(ApiMessages $apiMessages)
    {
        $this->messages = $apiMessages;
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
