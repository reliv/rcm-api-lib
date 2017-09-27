<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ApiPopulatableInterface
{
    /**
     * populate
     *
     * @param array $data
     * @param array $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populate(
        array $data,
        array $ignore = []
    );

    /**
     * populateFromObject
     *
     * @param ApiPopulatableInterface $object Object of THIS type
     * @param array                   $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    );
}
