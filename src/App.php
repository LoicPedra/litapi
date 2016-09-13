<?php

namespace LitApi;

use Router\Route;

/**
 * Main class which allows all features of LitApi
 * @package LitApi
 */
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

    /**
     * Add GET route to available request
     * @param $path String Url to match with
     * @param $callable String If it's a closure then it will be called if url matches with $path.
     * Else the string must to match to "ControllerClassName#MethodName"
     * @return Route
     */
	public function get($path, $callable)
	{
		return $this->request("GET", $path, $callable);
	}

    /**
     * Add POST route to available request
     * @param $path String Url to match with
     * @param $callable String If it's a closure then it will be called if url matches with $path.
     * Else the string must to match to "ControllerClassName#MethodName"
     * @return Route
     */
	public function post($path, $callable)
	{
		return $this->request("POST", $path, $callable);
	}

    /**
     * @param $method String GET, POST, PUT or what method you want
     * @param $path String Url to match with
     * @param $callable String If it's a closure then it will be called if url matches with $path.
     * Else the string must to match to "ControllerClassName#MethodName"
     * @return Route
     */
	public function request($method, $path, $callable)
	{
		$route = new Route($method, $path, $callable);
		$this->routes[$method][] = $route;

		return $route;
	}

    /**
     * Launch parsing of the current request
     * and handle exceptions
     */
	public function run()
	{
		try
		{
			$this->search();
		}
		catch(Exception\NotFoundException $nfe)
		{
			if($this->notFoundClosure != null)
				call_user_func_array($this->notFoundClosure, array(new Exception\BadRequestException(null)));
			else
				call_user_func_array($this->exceptionClosure, array($nfe));
		}
        catch(Exception\BadRequestException $bre)
        {
            if($this->badRequestClosure != null)
                call_user_func_array($this->badRequestClosure, array(new Exception\NotImplementedException(null)));
            else
                call_user_func_array($this->exceptionClosure, array($bre));
        }
		catch(\Exception $e)
		{
			call_user_func_array($this->exceptionClosure, array($e));
		}
		
	}

    /**
     * Search adequate route
     * @return mixed
     * @throws Exception\BadRequestException If no route is match to url
     */
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

    /**
     * Define not found closure
     * @param $nf callable closure
     */
	public function notFound($nf)
	{
		$this->notFoundClosure = $nf;
	}

    /**
     * Define bad request closure
     * @param $br callable closure
     */
	public function badRequest($br)
	{
		$this->badRequestClosure = $br;
	}

    /**
     * Define general closure for exception
     * @param $c callable closure
     */
	public function exception($c)
	{
		$this->exceptionClosure = $c;
	}
}

?>