<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\BaseErrorModel;
use Reliv\RcmApiLib\Resource\Model\BaseRouteModel;
use Reliv\RcmApiLib\Resource\Model\ErrorModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * class ZfConfigErrorModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigErrorModelProvider extends ZfConfigAbstractModelProvider implements ModelProvider
{
    /**
     * @var ErrorModel
     */
    protected $errorModel;

    /**
     * get
     *
     * @return ErrorModel
     */
    public function get()
    {
        if (empty($this->errorModel)) {
            $services = $this->buildServiceArray(
                $this->config['Reliv\\RcmApiLib']['resource']['errorServiceNames']
            );
            // Options cannot be supported
            $this->errorModel = new BaseErrorModel(
                $services,
                []
            );
        }

        return $this->errorModel;
    }
}
