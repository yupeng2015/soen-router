<?php


namespace Soen\Router;


use Soen\Filesystem\File;
use Soen\Http\Message\Request;
use Soen\Http\Message\Response;

class RouterProvider
{
    public $routes;
    public $routeCurrent;
	function __construct($routesPath)
	{
	    $this->parse($routesPath);
	}

	function parse($routesPath){
        $files = (new File())->createFilesystemIterator($routesPath);
        $files->readFilesRequire($routesPath);
	}

    /**
     * @param Request $request
     * @return RouteCurrent|null
     */
	function setRouteCurrent(Request $request){
		$this->routeCurrent = (Router::new())->getRouteCurrent($request);
		return $this->routeCurrent;
	}

	function getRouteCurrent(Request $request, Response $response){
		return $this->routeCurrent;
	}
}