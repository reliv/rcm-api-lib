<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ConfigMethod
 *
 * PHP version 5
 *
 * @category  Reliv
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ConfigMethod implements Method
{
    /**
     * @var Options
     */
    protected $methodOptions;

    /**
     * ConfigMethod constructor.
     *
     * @param Options $methodOptions
     */
    public function __construct(Options $methodOptions)
    {
        $this->methodOptions = $methodOptions;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->methodOptions->get('description', '');
    }

    /**
     * getHttpVerb
     *
     * @return string
     */
    public function getHttpVerb()
    {
        return strtoupper($this->methodOptions->get('httpVerb', 'GET'));
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath()
    {
        return $this->methodOptions->get('path');
    }

    /**
     * getPreService
     *
     * @return array
     */
    public function getPreServices()
    {
        return $this->methodOptions->get('preServices', []);
    }

    /**
     * getPreService
     *
     * @param string $name
     *
     * @return array
     */
    public function hasPreService($name)
    {
        $preServices = $this->getPreServices();

        return !empty($preServices);
    }

    /**
     * getPreOptions
     *
     * @param string $name
     *
     * @return Options
     */
    public function getPreOptions($name)
    {
        return $this->methodOptions->getOptions('preOptions');
    }
}
