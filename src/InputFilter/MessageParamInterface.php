<?php

namespace Reliv\RcmApiLib\InputFilter;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface MessageParamInterface
{
    /**
     * getMessageParams
     *
     * @return array
     */
    public function getMessageParams();

    /**
     * setMessageParams
     *
     * @param $messageParams ['key' => 'value']
     *
     * @return void
     */
    public function setMessageParams(array $messageParams);
}
