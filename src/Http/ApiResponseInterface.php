<?php


namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class ApiResponseInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Http
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
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
