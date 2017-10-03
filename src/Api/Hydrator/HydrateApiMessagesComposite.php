<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesComposite implements HydrateApiMessages
{
    /**
     * @var array
     */
    protected $hydrators = [];

    /**
     * add
     *
     * @param HydrateApiMessages $hydrator
     *
     * @return void
     */
    public function add(HydrateApiMessages $hydrator)
    {
        $this->hydrators[] = $hydrator;
    }

    /**
     * hydrate
     *
     * @param mixed       $apiMessageData
     * @param ApiMessages $apiMessages
     *
     * @return null|ApiMessages
     * @throws CanNotHydrate
     */
    public function __invoke($apiMessageData, ApiMessages $apiMessages)
    {
        /** @var HydrateApiMessages $hydrate */
        foreach ($this->hydrators as $hydrate) {
            try {
                $result = $hydrate->__invoke($apiMessageData, $apiMessages);
            } catch (\Exception $exception) {
                // Cant do it :)
                $result = null;
            }

            if ($result !== null) {
                return $result;
            }
        }

        throw new CanNotHydrate(
            get_class($this) . ' cannot hydrate this data ' . $this->getTypeDetails($apiMessageData)
        );
    }

    /**
     * @param mixed $apiMessageData
     *
     * @return string
     */
    protected function getTypeDetails($apiMessageData) {

        $message = "type: " . gettype($apiMessageData) . " \n";

        if(is_object($apiMessageData)) {
            $message .= "class: " . get_class($apiMessageData) . " \n";
        } else {
            $message .= "data: " . json_encode($apiMessageData, 0, 5) . " \n";
        }

        return $message;
    }
}
