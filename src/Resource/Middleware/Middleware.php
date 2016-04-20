<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

/**
 * Class Middleware
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   MiddlewareInterface
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface Middleware extends \Zend\Stratigility\MiddlewareInterface
{
    /**
     * REQUEST_ATTRIBUTE_MIDDLEWARE_OPTIONS
     */
    const REQUEST_ATTRIBUTE_MIDDLEWARE_OPTIONS = 'api-lib-middleware-options';

    const REQUEST_ATTRIBUTE_RESPONSE_FORMAT_SERVICE = 'api-lib-resource-response-format-service';
}
