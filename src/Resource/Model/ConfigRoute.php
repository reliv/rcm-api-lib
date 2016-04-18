<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ConfigRoute
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ConfigRoute
{
    /**
     * @var Options
     */
    protected $defaultOptions;

    /**
     * ConfigRoute constructor.
     *
     * @param Options $defaultOptions
     */
    public function __construct(
        Options $defaultOptions
    ) {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * getRouteService
     *
     * @return string|null
     */
    public function getRouteService()
    {
        return $this->defaultOptions->get('routeService', null);
    }

    /**
     * getRouteOptions
     *
     * @return Options
     */
    public function getRouteOptions()
    {
        return $this->defaultOptions->getOptions('routeOptions');
    }
}
