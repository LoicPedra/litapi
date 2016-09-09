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
			/*if($this->notFoundClosure != null)
				call_user_func_array($this->notFoundClosure, array(new Exception\BadRequestException(null)));
			else*/
				throw new Exception\BadRequestException(null);
		}

		foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
			if($route->match($this->url))
			{
				return $route->call();
			}
		}
/*
		if($this->badRequestClosure != null)
				call_user_func_array($this->badRequestClosure, array(new Exception\NotImplementedException(null)));
		else*/
			throw new Exception\NotImplementedException(null);
	}

	public function notFound($nf)
	{
		$this->notFoundClosure = $nf;
	}

	public function badRequest($br)
	{
		$this->badRequestClosure = $br;
	}
}

?>