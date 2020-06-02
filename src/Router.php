<?php
declare(strict_types=1);

namespace Soen\Router;


use Soen\Filesystem\File;

class Router
{
	public static $routes = [];

	function __construct()
    {
    }

    public static function new(){
	  new static();
    }

    public static function add(array $methods, string $route, array $controllerAction, array $middlewares)
	{
	    foreach ($methods as &$method){
            $key = strtoupper($method) .'-'. $route;
            self::$routes[$key] = [
                'methods'           =>  $methods,
                'route'             =>  $route,
                'controllerAction'  =>  $controllerAction,
                'middlewares'       =>  $middlewares
            ];
        }
	    return $key;
	}

	/**
	 * @param array $middlewares
	 * @param array $routes
	 */
	public static function group(array $middlewares, array $routes){
	    array_walk($routes, function ($routeKey)use($middlewares){
            self::$routes[$routeKey]['middlewares'] = self::$routes[$routeKey]['middlewares'] + $middlewares['middle'];
        });
    }

	/**
	 * @param string $route
	 * @param array $controllerAction
	 * @param array $middlewares
	 * @return string
	 */
    public static function get(string $route, array $controllerAction, array $middlewares) {
        return self::add(['get'], $route, $controllerAction, $middlewares);
    }
    public static function post(string $route, array $controllerAction, array $middlewares) {
	    return self::add(['post'], $route, $controllerAction, $middlewares);
    }
    public static function put(string $route, array $controllerAction, array $middlewares) {
	    return self::add(['put'], $route, $controllerAction, $middlewares);
    }
    public static function delete(string $route, array $controllerAction, array $middlewares) {
	    return self::add(['delete'], $route, $controllerAction, $middlewares);
    }
    public function set(array $methods, string $route, array $controllerAction){

    }
	public function getRoute(string $routeKey = null)
	{
	    if ($routeKey) {
	        return self::$routes[$routeKey];
        }
	    return null;
	}

	public function test(){
	    echo 'this is test';
    }
}