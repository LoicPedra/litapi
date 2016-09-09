<?php

namespace LitApi\Debug;

class Logger
{

	private static $debugEnabled = false;

	public static function log($class, $str)
	{
		if(Logger::$debugEnabled)
			echo "<p>[LOG] [" . get_class($class) . "] $str </p>";
	}

	public static function warning($class, $str)
	{
		if(Logger::$debugEnabled)
			echo "<p>[WARNING] [" . get_class($class) . "] $str </p>";
	}

	public static function error($class, $str)
	{
		if(Logger::$debugEnabled)
			echo "<p>[ERROR] [" . get_class($class) . "] $str </p>";
	}

	public static function enableDebug()
	{
		Logger::$debugEnabled = true;
	}

	public static function disableDebug()
	{
		Logger::$debugEnabled = false;
	}
}

?>