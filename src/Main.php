<?php


namespace Soen\Router;


use Soen\Http\Message\Request;
use Soen\Http\Message\Response;

class Main
{
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

	function __construct()
	{
	}

	function run (Request $request, Response $response){

	}
}