<?php

namespace Router;

class Route
{

	private $path;

	private $callable;

	private $matches = array();

	private $params = array();

	public function __construct($path, $callable)
	{
		$this->path = trim($path, "/");
		$this->callable = $callable;
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
			require_once("src/Controller/".$p[0].".php");
			$controller = $p[0];
			$controller = new $controller();
			return call_user_func_array([$controller, $p[1]], $this->matches);

		}
		else
		{
			//return call_user_func_array($this->callable, array(array('args1' => '1', 'args2' => '2')));
			return call_user_func_array($this->callable, array($this->matches));
		}
			
	}
}

?>