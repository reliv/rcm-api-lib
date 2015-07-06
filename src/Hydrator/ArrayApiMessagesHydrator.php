<?php


namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class ArrayApiMessageHydrator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\Hydrator\Http
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ArrayApiMessagesHydrator implements ApiMessagesHydratorInterface
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
        if (is_array($data) && array_key_exists('value', $data) && array_key_exists('type', $data)) {
            $apiMessage = new ApiMessage(
                $data['type']
            );

            $apiMessage->populate($data);

            $apiMessages->add($apiMessage);

            return $apiMessages;
        }

        throw new ApiMessagesHydratorException(
            get_class($this) . ' cannot hydrate this data type'
        );
    }
}
