<?php
declare(strict_types=1);

namespace Soen\Router;


class Router
{
	public static $routes = [];


	public static function addRoute(array $methods, string $route, array $controllerAction, callable $func)
	{
	    foreach ($methods as &$method){
            $key = strtoupper($method) .'-'. $route;
            self::$routes[$key] = [
                'methods'           => $methods,
                'route'             =>  $route,
                'controllerAction'  =>  $controllerAction,
                'middlewares'       =>  $func()
            ];
        }
	}

	public function getRoute(string $routeKey = null)
	{
	    if ($routeKey) {
	        return self::$routes[$routeKey];
        }
	    return null;
	}
}