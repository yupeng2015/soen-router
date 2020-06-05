<?php
declare(strict_types=1);

namespace Soen\Router;


use Psr\Http\Message\RequestInterface;
use Soen\Filesystem\File;
use Soen\Http\Message\Request;
use Soen\Router\Exception\NotFoundException;

class Router
{
	public static $routes = [];
	public static $methodGroup;

	function __construct()
    {
    }

    public static function new(){
	  return new static();
    }

    public static function add(array $methods, string $path, array $controllerAction, array $middlewares)
	{

	    foreach ($methods as $method){
	        self::$methodGroup[$method][] = $path;
            $key = $method .'-'. $path;
            self::$routes[$key] = [
                'methods'           =>  $methods,
                'path'             =>  $path,
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
    public static function get(string $path, array $controllerAction, array $middlewares) {
        return self::add(['get'], $path, $controllerAction, $middlewares);
    }
    public static function post(string $path, array $controllerAction, array $middlewares) {
	    return self::add(['post'], $path, $controllerAction, $middlewares);
    }
    public static function put(string $path, array $controllerAction, array $middlewares) {
	    return self::add(['put'], $path, $controllerAction, $middlewares);
    }
    public static function delete(string $path, array $controllerAction, array $middlewares) {
	    return self::add(['delete'], $path, $controllerAction, $middlewares);
    }
    public function set(array $methods, string $path, array $controllerAction){

    }

    /**
     * 解析匹配规则的路由
     * @param string $urlPath
     * @param string $urlMethod
     * @return bool|string
     */
    public function parsePath(string $urlPath, string $urlMethod){
        $urlMethod = strtolower($urlMethod);
        $urlPathArr = explode('/', $urlPath);
        array_shift($urlPathArr);
        $urlPathStrCount = count($urlPathArr);
        foreach (self::$methodGroup[$urlMethod] as $routePath){
            $routePathArr = explode('/', $routePath);
            array_shift($routePathArr);
            $routePathStrCount = count($routePathArr);
            //如果定义的路由 长度小于 请求的url 肯定无法匹配
            if ($routePathStrCount < $urlPathStrCount) {
                continue;
            }
            $i = 0;
            $params = [];
            foreach ($routePathArr as $key => $str){
                $strArr = [];
                $regular = false;
                if(strpos($str,'{') !== false){
                    $regular = true;
                    $str = ltrim($str,'{');
                    $str= rtrim($str, '}');
                    $strArr = explode(':', $str);
                    //非必填路由参数
                    if($notRequired = substr($str,0,1) == '?'){
                        $strArrLeftVal = ltrim($strArr[0], '?');
                    } else {
                        $strArrLeftVal = $strArr[0];
                    }
                    $strArrRightVal = $strArr[1];
                } else {
                    $strArrLeftVal = $str;
                }
                if(!isset($urlPathArr[$key])){
                    if(!$notRequired){
                        break;
                    }
                } else {
                    if($regular){
                        if(!preg_match('/' . $strArrRightVal . '/', urldecode($urlPathArr[$key]))){
                            break;
                        }
                        $params += [
                            $strArrLeftVal =>  $urlPathArr[$key]
                        ];
                    } else {
                        if($strArrLeftVal != $urlPathArr[$key]){
                            break;
                        }
                    }
//                    $match = $regular ? !preg_match($strArrRightVal, $urlPathArr[$key]) : $strArrLeftVal != $urlPathArr[$key];
//                    var_dump($regular, $match, $strArrRightVal, $urlPathArr[$key]);
//                    if ($match) {
//                        break;
//                    }
//                    if($regular && !preg_match($strArrRightVal, $urlPathArr[$key])){
//                        break;
//                    } else if($strArrLeftVal != $urlPathArr[$key]){
//                        break;
//                    }
                }
                $i++;
            }
            if($i == $routePathStrCount){
                return [self::$routes[$urlMethod . '-' . $routePath], $params];
            }
//            $hasRoute = preg_match($method . '-' . $path, $route, $matches);
//            if($hasRoute){
//                return $matches[0];
//            }
        }
        return false;
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    public function makeRouteKey(RequestInterface $request){
        return $request->getMethod() . '-' . $request->getUri()->getPath();
    }

    /**
     * @param RequestInterface $request
     * @return RouteCurrent|null
     */
	public function getRouteCurrent(RequestInterface $request)
	{
        $path = $request->getUri()->getPath();
	    if (!$path) {
            throw new NotFoundException('404');
        }
        $routeKey = $this->makeRouteKey($request);
        $routeCurrent = null;
        //通过索引直接拿到索引
        if (isset(self::$routes[$routeKey])) {
            return $routeCurrent = new RouteCurrent(self::$routes[$routeKey]);
        }
        list($route, $params) = $this->parsePath($path, $request->getMethod());
	    // 解析 带匹配规则的路由
	    if (list($route, $params) = $this->parsePath($path, $request->getMethod())) {
            return new RouteCurrent($route, $params);
        }
        throw new NotFoundException('404');
	}

	public function test(){
	    echo 'this is test';
    }
}