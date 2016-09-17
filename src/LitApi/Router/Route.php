<?php

namespace Router;

class Route
{

    private $method;

	private $path;

	private $callable;

	private $matches = array();

	private $params = array();

	public function __construct($method, $path, $callable)
	{
		$this->path = trim($path, "/");
		$this->callable = $callable;
		$this->method = $method;
	}

	public function with($param, $regex)
	{
		$this->params[$param] = str_replace('(', '(?:', $regex);

		return $this;
	}

	public function match($url)
	{
		$url = trim($url, "/");

		$path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);

		$regex = "#^".$path."$#i";

		if(!preg_match($regex, $url, $matches))
		{
			return false;
		}

		array_shift($matches);

		$this->matches = $matches;

		return true;
	} 

	private function paramMatch($match)
	{
		if(isset($this->params[$match[1]]))
		{
			return '(' . $this->params[$match[1]] . ')';
		}
		return '([^/+]+)';
	}

	public function call()
	{
		if(is_string($this->callable))
		{
			$p = explode('#', $this->callable);
			$controller = "\\Controller\\" . $p[0];
			$controller = new $controller();
			return call_user_func_array([$controller, $p[1]], array(new RouterRequest($this->matches, $this->method)));
//			return call_user_func_array([$controller, $p[1]], $this->matches);

		}
		else
		{
			return call_user_func_array($this->callable, array(new RouterRequest($this->matches, $this->method)));
//			return call_user_func_array($this->callable, array($this->matches));
		}
			
	}
}

?>