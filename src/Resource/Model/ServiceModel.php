<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\ServiceMissingException;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ServiceModel
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
interface ServiceModel
{
    /**
     * getAlias
     *
     * @return string
     */
    public function getAlias();
    
    /**
     * getService
     *
     * @return object  Middleware compatible
     * @throws ServiceMissingException
     */
    public function getService();

    /**
     * getPreOptions
     *
     * @return Options
     */
    public function getOptions();

}
