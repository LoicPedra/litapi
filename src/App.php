<?php

namespace LitApi;

use \Router\Route;

class App
{
	
	private $url;

	private $routes = array();

	function __construct()
	{
		$this->url = isset($_GET['url']) ? $_GET['url'] : "/";
	}

	public function get($path, $callable)
	{
		$route = new Route($path, $callable);
		$this->routes['GET'][] = $route;

		return $route;
	}

	public function post($path, $callable)
	{
		$route = new Route($path, $callable);
		$this->routes['post'][] = $route;

		return $route;
	}

	public function run()
	{
		if(!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
		{
			//throw new BadRequestException(null);
		}

		foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
			if($route->match($this->url))
			{
				return $route->call();
			}
		}

		//throw new BadRequestException(null);
	}
}

?>