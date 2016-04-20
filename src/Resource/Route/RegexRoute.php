<?php
namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class Router
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RegexRoute extends AbstractRoute
{
    /**
     * match
     *
     * @param Request $request
     *
     * @return bool
     * @throws RouteException
     */
    public function match(Request $request, Options $options)
    {
        /** @var RouteModel $routeModel */
        $routeModel = $request->getAttribute(RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE);

        $path = $options->get('path');
        $httpVerb = $options->get('httpVerb');

        if(empty($path)) {
            throw new RouteException('Path option required');
        }
        
        // '#/api/resource/(?<resourcePath>[a-z]+)/(?<resourceMethod>[^.]+)#';
        // $regex = '/\/api\/resource\/(?<resourcePath>[a-z]+)\/(?<resourceMethod>[^.]+)/';
        $regex = "#{$path}#";
        
        // $uri = '/api/resource/hi/there/oh/yeah';
        $uri = $request->getUri()->getPath();
        $routeMatched = (bool)preg_match($regex, $uri, $captures);
        $verbMatched = ($request->getMethod() === $httpVerb);
        
        if(!$routeMatched || !$verbMatched){
            return false;
        }

        foreach ($captures as $key => $val) {
            if (!is_numeric($key)) {
                $routeModel->setRouteParam($key, $val);
            }
        }

        return true;
    }
}
