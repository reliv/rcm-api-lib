<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ApiSerializableInterface extends \JsonSerializable
{
    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return mixed
     */
    public function toArray($ignore = []);
}
