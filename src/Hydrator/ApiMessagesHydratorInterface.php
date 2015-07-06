<?php

namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Interface ApiMessageHydratorInterface
 *
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\Hydrator\Http
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface ApiMessagesHydratorInterface
{
    /**
     * hydrate - Hydrate ApiResponse with data
     * - On Success - return ApiResponse
     * - On Fail    - ApiMessagesHydratorException
     *
     * @param             $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws \Reliv\RcmApiLib\Exception\ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages);
}
