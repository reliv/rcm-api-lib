<?php

namespace Reliv\RcmApiLib\Api\Translate;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildStringParams
{
    /**
     * @param array $params
     *
     * @return array
     */
    public static function invoke(array $params = [])
    {
        $stringParams = [];

        foreach ($params as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $stringParams[$key] = $value;
                continue;
            }
            $stringParams[$key] = json_encode($value);
        }

        return $stringParams;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function __invoke(array $params = []): array
    {
        return self::invoke($params);
    }
}
