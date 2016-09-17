<?php

namespace LitApi;

/**
 * Autoload all classes from LitApi
 * @package LitApi
 */
class Autoloader
{

    /**
     * Register a new autoload for LitApi
     */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Common autoload
     * @param $class mixed The current class
     */
    static function autoload($class)
    {
        $class = str_replace(__NAMESPACE__ . "\\", '', $class);
        $class = str_replace("\\", '/', $class);
        require_once 'src/' . __NAMESPACE__ . '/' . $class . '.php';
    }

}

Autoloader::register();

?>