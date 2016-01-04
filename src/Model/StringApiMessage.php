<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\Http\ApiResponse;

/**
 * Class ApiMessage
 *
 * API message format
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Message
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
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
        $type = 'stringMessage',
        $source = 'generic',
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
