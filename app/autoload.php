<?php

/**
 * Autoload required classes
 */
function __autoload($className)
{
    /* echo ROOT . DS . 'src' . DS . 'controllers' . DS . $className . 'Controller.php'; */
    if (file_exists(ROOT . DS . 'app' . DS . $className . '.php'))
    {
        require_once(ROOT . DS . 'app' . DS . $className . '.php');
    }
    else if (file_exists(ROOT . DS . 'src' . DS . 'controllers' . DS . $className . '.php'))
    {
        require_once(ROOT . DS . 'src' . DS . 'controllers' . DS . $className . '.php');
    }
    else
    {
        //throw error here
    }
}
