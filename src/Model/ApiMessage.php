<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\ApiResponse;

/**
 * Class ApiMessage
 *
 * API message format
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Message
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiMessage extends AbstractApiModel
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var string
     */
    protected $value;
    /**
     * @var string
     */
    protected $type = ApiResponse::PRIMARY_TYPE;
    /**
     * @var string|null
     */
    protected $code = null;
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param       $key
     * @param       $value
     * @param       $type
     * @param null  $code
     * @param array $params
     */
    public function __construct(
        $key,
        $value = '',
        $type = ApiResponse::PRIMARY_TYPE,
        $code = null,
        $params = []
    ) {
        $this->setKey($key);
        $this->setValue($value);
        $this->setType($type);
        $this->setCode($code);
        $this->setParams($params);
    }

    /**
     * getKey
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * setKey
     *
     * @param $key
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = (string)$key;
    }

    /**
     * getValue
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * setValue
     *
     * @param $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = (string)$value;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setType
     *
     * @param $type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = (string)$type;
    }

    /**
     * getCode
     *
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * setCode
     *
     * @param $code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = (string)$code;
    }

    /**
     * getParams
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * setParams
     *
     * @param $params
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = (array)$params;
    }

    /**
     * getParam
     *
     * @param string $key
     * @param null   $default
     *
     * @return null|mixed
     */
    public function getParam($key, $default = null)
    {
        $key = (string)$key;
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return $default;
    }

    /**
     * setParam
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setParam($key, $value)
    {
        $key = (string)$key;
        $this->params[$key] = $value;
    }
}
