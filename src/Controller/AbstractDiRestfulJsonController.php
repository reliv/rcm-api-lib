<?php

namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Service\ResponseService;

/**
 * Class AbstractRestfulJsonController
 *
 * ZF2 AbstractRestfulController returns arrays for missing methods
 * This allows proper responses to be returned
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractDiRestfulJsonController extends AbstractRestfulJsonController
{
    /**
     * @override
     * @var ApiResponse
     */
    protected $responseService;

    /**
     * AbstractDiRestfulJsonController constructor.
     *
     * @param ResponseService $responseService
     */
    public function __construct(
        ResponseService $responseService
    ) {
        $this->responseService = $responseService;
    }

    /**
     * getResponseService
     *
     * @return ResponseService
     */
    protected function getResponseService()
    {
        return $this->responseService;
    }
}
