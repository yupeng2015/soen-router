<?php


namespace Soen\Router;


use Soen\Filesystem\File;

class Provider
{
    public $routes;
    
	function __construct($routesPath)
	{
	    $this->parse($routesPath);
	}

	function parse(){
        $files = new File();
        $files->readFilesRequire($routesPath);
	}
}