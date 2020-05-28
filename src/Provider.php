<?php


namespace Soen\Router;


use Soen\Filesystem\File;
use Soen\Http\Message\Request;
use Soen\Http\Message\Response;

class Provider
{
    public $routes;
    public $routeActive;
	function __construct($routesPath)
	{
	    $this->parse($routesPath);
	}

	function parse($routesPath){
        $files = (new File())->createFilesystemIterator($routesPath);
        $files->readFilesRequire($routesPath);
	}

	function setRouteActive(Request $request, Response $response){
		$this->routeActive = new RouteActive($request, $response);
	}

	function getRouteActive(Request $request, Response $response){
		return $this->routeActive;
	}
}