<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Http\Message\Response;

class Main
{

    /**
     * @var Router
     */
    public $router;
    /**
     * 所有路由集合
     * @var array
     */
    public $routes;
	/**
	 * 请求方法
	 * @var null
	 */
	public $method = null;

	/**
	 * 中间件
	 * @var array
	 */
	public $middleware = [];

	function __construct(Router $router)
	{
	    $this->router = $router;
	    $this->routes = $this->router->getRoutes();
	}

	function run (Request $request, Response $response){
        
	}
}