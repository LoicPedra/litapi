<?php

namespace LitApi;

use Router\Route;

class App
{
	
	private $url;

	private $routes = array();

	private $notFoundClosure = null;
	private $badRequestClosure = null;

	private $exceptionClosure;

	function __construct()
	{
		$this->url = isset($_GET['url']) ? $_GET['url'] : "/";
		$this->exceptionClosure = function($e)
		{
			echo "Exception: $e";
		};
	}

	public function get($path, $callable)
	{
		//$route = new Route($path, $callable);
		//$this->routes['GET'][] = $route;

		return $this->request("GET", $path, $callable);
	}

	public function post($path, $callable)
	{
		//$route = new Route($path, $callable);
		//$this->routes['POST'][] = $route;

		return $this->request("POST", $path, $callable);
	}

	public function request($method, $path, $callable)
	{
		$route = new Route($path, $callable);
		$this->routes[$method][] = $route;

		return $route;
	}

	public function run()
	{
		try
		{
			$this->search();
		}
		catch(Exception\NotImplementedException $nie)
		{
			if($this->badRequestClosure != null)
				call_user_func_array($this->badRequestClosure, array(new Exception\NotImplementedException(null)));
			else
				call_user_func_array($this->exceptionClosure, array($nie));
		}
		catch(Exception\BadRequestException $bre)
		{
			if($this->notFoundClosure != null)
				call_user_func_array($this->notFoundClosure, array(new Exception\BadRequestException(null)));
			else
				call_user_func_array($this->exceptionClosure, array($bre));
		}
		catch(\Exception $e)
		{
			call_user_func_array($this->exceptionClosure, array($e));
		}
		
	}

	private function search()
	{
		if(!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
		{
			throw new Exception\BadRequestException(null);
		}

		foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
			if($route->match($this->url))
			{
				return $route->call();
			}
		}

		//throw new Exception\NotImplementedException(null);

		throw new Exception\BadRequestException(null);
	}

	public function notFound($nf)
	{
		$this->notFoundClosure = $nf;
	}

	public function badRequest($br)
	{
		$this->badRequestClosure = $br;
	}

	public function exception($e)
	{
		$this->exceptionClosure = $e;
	}
}

?>