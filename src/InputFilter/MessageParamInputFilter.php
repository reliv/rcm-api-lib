<?php

namespace Reliv\RcmApiLib\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class MessageParamInputFilter extends InputFilter implements MessageParamInterface
{
    /**
     * @var array
     */
    protected $messageParams = [];

    /**
     * getMessageParams
     *
     * @return array
     */
    public function getMessageParams()
    {
        return $this->messageParams;
    }

    /**
     * setMessageParams
     *
     * @param $messageParams ['key' => 'value']
     *
     * @return void
     */
    public function setMessageParams(array $messageParams)
    {
        $this->messageParams = $messageParams;
    }
}
