<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Router\Exception\NotFoundException;

class RouteCurrent
{
	/**
	 * @var array
	 */
	public $route;
    public $path;
    public $middlewares;
    public $methods;
    public $classAction = [];
    public $params = [];
    
    function __construct(array $route, array $params = [])
    {
        if (!$route) {
            throw new NotFoundException('Not Found (#404)');
        }
        $this->route = $route;
        $this->path = $this->route['path'];
        $this->method = $this->route['methods'];
        $this->classAction = $this->route['controllerAction'];
        $this->middlewares = $this->route['middlewares'];
        if (!empty($params)) {
            $this->params = $params;
        }
    }

	/**
	 * @return array
	 */
	public function getRoute():array
	{
		return $this->route;
	}

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
    public function getMiddlewares(){
        return $this->middlewares;
    }

    public function getMethods(){
    	return $this->methods;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
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