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

	public static function group(array $middlewares, array $routes){
	    array_walk($routes, function ($routeKey, $key){
            var_dump(self::$routes[$routeKey]['middlewares']);
            self::$routes[$routeKey]['middlewares'] = self::$routes[$routeKey]['middlewares'] + $middlewares['middle'];
        });

    }

    public static function get(string $route, array $controllerAction, array $middlewares) {
        return self::add(['get'], $route, $controllerAction, $middlewares);
    }
    public static function post() {

    }
    public static function put() {

    }
    public static function delete() {

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