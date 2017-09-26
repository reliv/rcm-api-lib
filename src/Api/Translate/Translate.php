<?php

namespace Reliv\RcmApiLib\Api\Translate;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Translate
{
    /**
     * @param string $message
     * @param array  $params
     * @param array  $options
     *
     * @return string
     */
    public function __invoke(
        string $message,
        array $params,
        array $options = []
    ):string;
}
