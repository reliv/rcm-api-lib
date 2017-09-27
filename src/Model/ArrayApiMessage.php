<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ArrayApiMessage extends ApiMessage
{
    /**
     * @param array $properties
     * @param array $ignore
     */
    public function __construct(
        $properties = [],
        $ignore = []
    ) {
        $this->build(
            $properties,
            $ignore
        );
    }

    protected function build(
        $properties = [],
        $ignore = []
    ) {
        if (!isset($properties['value'])) {
            $properties['value'] = '';
        }

        parent::populate($properties, $ignore);
    }
}
