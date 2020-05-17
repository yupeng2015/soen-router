<?php
declare(strict_types=1);

namespace Soen\Router;


class Router
{
	public static $routes = [];


	public static function addRoute(array $methods, string $route, array $path, callable $func)
	{
		array_push(self::$routes, [
			'methods'       => $methods,
			'route'         =>  $route,
			'path'          =>  $path,
			'middlewares'   =>  $func()
		]);
	}

	public function middlewares()
	{

	}
}