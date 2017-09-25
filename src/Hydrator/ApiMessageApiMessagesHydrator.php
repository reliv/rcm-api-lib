<?php

namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class ApiMessageApiMessagesHydrator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Hydrator
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiMessageApiMessagesHydrator implements ApiMessagesHydratorInterface
{
    /**
     * hydrate
     *
     * @param mixed       $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        if (!$data instanceof ApiMessage) {
            throw new ApiMessagesHydratorException(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $apiMessages->add($data);

        return $apiMessages;
    }
}
