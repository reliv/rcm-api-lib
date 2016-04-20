<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\ServiceMissingException;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class AbstractServiceModel
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Resource\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractServiceModel
{
    /**
     * @var Middleware compatible
     */
    protected $service;

    /**
     * @var Options
     */
    protected $serviceOptions;

    /**
     * AbstractServiceModel constructor.
     *
     * @param object  $service Middleware compatible
     * @param Options $serviceOptions
     */
    public function __construct(
        $service,
        Options $serviceOptions
    ) {
        $this->service = $service;
        $this->serviceOptions = $serviceOptions;
    }

    /**
     * getService
     *
     * @return object Middleware compatible
     * @throws ServiceMissingException
     */
    public function getService()
    {
        if (empty($this->service)) {
            throw new ServiceMissingException('Service not set');
        }

        return $this->service;
    }

    /**
     * getPreOptions
     *
     * @return Options
     */
    public function getOptions()
    {
        return $this->serviceOptions;
    }
}
