<?php


namespace Reliv\RcmApiLib\Model;

/**
 * Class ApiMessages
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class ApiMessages extends AbstractApiModel implements \IteratorAggregate
{

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * add
     *
     * @param ApiMessage $apiMessage
     *
     * @return void
     */
    public function add(ApiMessage $apiMessage)
    {
        if ($apiMessage->isPrimary()) {
            array_unshift($this->messages, $apiMessage);

            return;
        }

        $this->messages[] = $apiMessage;
    }

    /**
     * get
     *
     * @param string $key
     * @param null $default
     *
     * @return null|ApiMessage
     */
    public function get($key, $default = null)
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

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return $this->messages;
    }

    /**
     * getIterator
     *
     * @return array
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}
