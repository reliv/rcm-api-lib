<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringApiMessage extends ApiMessage
{
    /**
     * @param string    $message
     * @param string    $type
     * @param string    $source
     * @param string    $code
     * @param null|bool $primary
     * @param array     $params
     */
    public function __construct(
        $message,
        $type = 'generic',
        $source = 'unknown',
        $code = 'fail',
        $primary = null,
        $params = []
    ) {
        parent::__construct(
            $type,
            $message,
            $source,
            $code,
            $primary,
            $params
        );
    }
}
