<?php

namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\InputFilter\MessageParamInputFilter;
use Reliv\RcmApiLib\InputFilter\MessageParamInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\InputFilterApiMessages;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class InputFilterApiMessageHydrator
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
class InputFilterApiMessagesHydrator implements ApiMessagesHydratorInterface
{
    /**
     * @var null|string
     */
    protected $primaryMessage = null;

    /**
     * @param $primaryMessage
     */
    public function __construct($primaryMessage)
    {
        $this->primaryMessage = $primaryMessage;
    }

    /**
     * hydrate
     *
     * @param             $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        if (!$data instanceof InputFilterInterface) {
            throw new ApiMessagesHydratorException(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $inputFilterApiMessages = new InputFilterApiMessages(
            $data,
            $this->primaryMessage
        );

        foreach ($inputFilterApiMessages as $apiMessage) {
            $apiMessages->add($apiMessage);
        }

        return $apiMessages;
    }
}
