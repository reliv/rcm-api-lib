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
        $index = $this->getIndex($apiMessage->getKey());

        if ($index !== null) {
            unset($this->messages[$index]);
        }

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
     * @param null   $default
     *
     * @return null|ApiMessage
     */
    public function get($key, $default = null)
    {
        $key = (string)$key;

        $index = $this->getIndex($key);

        if ($index !== null) {
            return $this->messages[$index];
        }

        return $default;
    }

    /**
     * getIndex
     *
     * @param $key
     *
     * @return int|null
     */
    protected function getIndex($key)
    {
        foreach ($this->messages as $index => $apiMessage) {
            if ($apiMessage->getKey() === $key) {
                return $index;
            }
        }

        return null;
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        // re-index so array is valid for json array
        return array_values($this->messages);
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
