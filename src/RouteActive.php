<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Router\Exception\NotFoundException;

class RouteActive
{
    public $route;
    public $middleware;
    public $method;
    public $classAction = [];
    
    function __construct(Request $request, Router $router)
    {
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
    
    function getMiddleware(){
        return $this->middleware;
    }
}