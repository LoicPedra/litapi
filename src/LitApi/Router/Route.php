<?php

namespace Router;

class Route
{

    private $method;

	private $path;

	private $callable;

	private $matches = array();

	private $params = array();

    private $layout_above  = array();

    private $layout_below  = array();

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
            $this->displayLayoutAbove();
			call_user_func_array([$controller, $p[1]], array(new RouterRequest($this->matches, $this->method)));
            $this->displayLayoutBelow();
//			return call_user_func_array([$controller, $p[1]], $this->matches);

		}
		else
		{
            $this->displayLayoutAbove();
			call_user_func_array($this->callable, array(new RouterRequest($this->matches, $this->method)));
            $this->displayLayoutBelow();
//			return call_user_func_array($this->callable, array($this->matches));
		}

		return;
	}

    public function setLayoutAbove($layout_above)
    {
        $this->layout_above = $layout_above;

        return $this;
    }

    public function setLayoutBelow($layout_below)
    {
        $this->layout_below = $layout_below;

        return $this;
    }

    private function displayLayoutAbove()
    {
        for($i = 0; $i < count($this->layout_above); $i++) {
            include $this->layout_above[$i];
        }
    }

    private function displayLayoutBelow()
    {
        for($i = 0; $i < count($this->layout_below); $i++) {
            include $this->layout_below[$i];
        }
    }
}

?>