<?php
namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Options\DefaultRouteOptions;

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
     * RegexRoute constructor.
     *
     * @param DefaultRouteOptions $defaultRouteOptions
     */
    public function __construct(DefaultRouteOptions $defaultRouteOptions)
    {
        $defaultOptions = $defaultRouteOptions->getOptions(
            'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController'
        );
        parent::__construct($defaultOptions);
    }
    
    /**
     * match
     *
     * @param Request $request
     * @param array   $options
     *
     * @return bool
     * @throws RouteException
     */
    public function match(Request $request, array $options)
    {
        $options = $this->buildRuntimeOptions($options);
        $path = $options->get($options);
        
        if(empty($path)) {
            throw new RouteException('Path option required');
        }
        
        //'#/api/resource/(?<resourcePath>[a-z]+)/(?<resourceMethod>[^.]+)#';
        //$regex = '/\/api\/resource\/(?<resourcePath>[a-z]+)\/(?<resourceMethod>[^.]+)/';
        $regex = "#{$path}#";
        
        $uri = '/api/resource/hi/there/oh/yeah';
        $routeMatched = (bool)preg_match($regex, $uri, $captures);
        
        if(!$routeMatched){
            return false;
        }
        
        $params = [];
        foreach ($captures as $key => $val) {
            if (!is_numeric($key)) {
                $params[$key] = $val;
            }
        }

        $request->withAttribute(Route::PARAMS_NAME, $params);

        return true;
    }
}
