<?php


namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ApiResponseInterface
{
    /**
     * setData
     *
     * @param array|null $data
     *
     * @return void
     */
    public function setData($data);

    /**
     * getData
     *
     * @return mixed
     */
    public function getData();

    /**
     * @param ApiMessages $apiMessages
     *
     * @return ApiResponseInterface
     */
    public function withApiMessages(ApiMessages $apiMessages);

    /**
     * addApiMessages
     *
     * @param array $apiMessages ApiMessage
     *
     * @return void
     */
    public function addApiMessages($apiMessages = []);

    /**
     * addApiMessage
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    public function addApiMessage(ApiMessage $apiMessage);

    /**
     * setApiMessages
     *
     * @param ApiMessages $apiMessages
     *
     * @return void
     */
    public function setApiMessages(ApiMessages $apiMessages);

    /**
     * getApiMessages
     *
     * @return ApiMessages
     */
    public function getApiMessages();

    /**
     * withStatus
     *
     * @param int    $statusCode
     * @param string $reasonPhrase
     *
     * @return ApiResponseInterface
     */
    public function withStatus($statusCode, $reasonPhrase = '');
}
