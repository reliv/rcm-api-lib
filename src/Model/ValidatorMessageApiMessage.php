<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidatorMessageApiMessage extends ApiMessage
{
    /**
     * @var string type for fields
     */
    protected $typeName = 'validatorMessage';

    /**
     * @param string $validatorMessage
     * @param string $fieldName
     * @param null   $errorKey
     * @param array  $params
     * @param null   $primary
     */
    public function __construct(
        $validatorMessage,
        $fieldName,
        $errorKey,
        $params = [],
        $primary = null
    ) {
        $this->setType($this->typeName);
        $this->setValue($validatorMessage);
        $this->setSource($fieldName);
        $this->setCode($errorKey);
        $this->setPrimary($primary);
        $this->setParams($params);
    }
}
