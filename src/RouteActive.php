<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Router\Exception\NotFoundException;

class RouteActive
{
	/**
	 * @var array
	 */
	public $routeActive;
    public $route;
    public $middleware;
    public $method;
    public $classAction = [];
    
    function __construct(Request $request)
    {
	    $router = new Router();
        $key = $request->getMethod() . '-' . $request->getUri()->getPath();
        $this->routeActive = $router->getRoute($key);
        if (!$this->routeActive ) {
            throw new NotFoundException('Not Found (#404)');
        }
        $this->route = $this->routeActive ['route'];
        $this->method = $request->getMethod();
        $this->classAction = $this->routeActive ['controllerAction'];
        $this->middleware = $this->routeActive ['middlewares'];
    }

	/**
	 * @return array
	 */
	public function getRoute():array
	{
		return $this->routeActive;
	}

    public function getMiddleware(){
        return $this->middleware;
    }

    public function getMethod(){
    	return $this->method;
    }

    /**
     * @return array|mixed
     */
    public function getClassAction($instance = false)
    {
        list($class, $action) = $this->classAction;
        if($instance){
            return [new $class, $action];
        }
        return $this->classAction;
    }
}