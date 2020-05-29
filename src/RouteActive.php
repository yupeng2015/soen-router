<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Router\Exception\NotFoundException;

class RouteActive
{
	/**
	 * @var array
	 */
    public $route;
    public $middleware;
    public $method;
    public $classAction = [];
    
    function __construct(Request $request)
    {
	    $router = new Router();
        $key = $request->getMethod() . '-' . $request->getUri()->getPath();
        $routeActive = $router->getRoute($key);
        if (!$routeActive) {
            throw new NotFoundException('Not Found (#404)');
        }
        $this->route = $routeActive['route'];
        $this->method = $request->getMethod();
        $this->classAction = $routeActive['controllerAction'];
        $this->middleware = $routeActive['middlewares'];
    }

	/**
	 * @return array
	 */
	public function getRoute():array
	{
		return $this->route;
	}

    public function getMiddleware(){
        return $this->middleware;
    }

    public function getMethod(){
    	return $this->method;
    }
}