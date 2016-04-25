<?php

namespace Reliv\RcmApiLib\Resource\Middleware\ResponseFormat;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;

/**
 * interface ResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResponseFormat extends Middleware
{
    /**
     * @var string
     */
    const NO_DATA_MODEL_SET = '_NO_DATA_MODEL_SET_';
}
