<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Reliv\RcmApiLib\Resource\Options\RuntimeOptions;

/**
 * Class ResourceController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResourceController extends RuntimeOptions
{
    /**
     * OPTIONS_ATTRIBUTE
     */
    const OPTIONS_ATTRIBUTE = 'resource-controller-options';
}
