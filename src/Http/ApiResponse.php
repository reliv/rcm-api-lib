<?php
namespace Reliv\RcmApiLib;

use Reliv\RcmApiLib\Model\ApiMessage;
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
     * PRIMARY TYPE Identifier
     */
    const PRIMARY_TYPE = 'primary';

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * setData
     *
     * @param \JsonSerializable $data
     *
     * @return void
     */
    public function setData(\JsonSerializable $data)
    {
        $this->data = $data;
    }

    /**
     * setDataArray
     *
     * @param array $data
     *
     * @return void
     */
    public function setDataArray(array $data)
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
     * setApiMessage
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    public function setApiMessage(ApiMessage $apiMessage)
    {
        if ($apiMessage->getKey() == self::PRIMARY_TYPE) {
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
