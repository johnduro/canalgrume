<?php

/** Check if environment is development and display errors **/ //DELETE ?

function setReporting()
{
    if (DEVELOPMENT_ENVIRONMENT == true)
    {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
    }
    else
    {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

/** Check for Magic Quotes and remove them **/ //DELETE ?

function stripSlashesDeep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes()
{
    if ( get_magic_quotes_gpc() )
    {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/** Check register globals and remove them **/ //DELETE ?

function unregisterGlobals()
{
    if (ini_get('register_globals'))
    {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value)
        {
            foreach ($GLOBALS[$value] as $key => $var)
            {
                if ($var === $GLOBALS[$key])
                {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}




/**
 * Handle request
 */

function handleRequest()
{
    /**
     * Url of the request
     */
    global $APP_URL;

    /**
     * Routes available
     */
    global $APP_ROUTES;

    echo "HANDLE REQUEST <br/>";//TODELETE

    $requestHandler = new RequestHandler($APP_ROUTES, $APP_URL);
    $requestHandler->treatRequest();
    /*
    $urlArray = array();
    $urlArray = explode("/",$url);

    $controller = $urlArray[0];
    array_shift($urlArray);
    $action = $urlArray[0];
    array_shift($urlArray);
    $queryString = $urlArray;

    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller, 's');
    $controller .= 'Controller';
    $dispatch = new $controller($model,$controllerName,$action);

    if ((int)method_exists($controller, $action))
    {
        call_user_func_array(array($dispatch,$action),$queryString);
    }
    else
    {
        // Error Generation Code Here
    }
    */
}

/** Autoload any classes that are required **/

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

    /* if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) */
    /* { */
    /*     require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php'); */
    /* } */
    /* else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) */
    /* { */
    /*     require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php'); */
    /* } */
    /* else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) */
    /* { */
    /*     require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php'); */
    /* } */
    /* else */
    /* { */
    /*     /\* Error Generation Code Here *\/ */
    /* } */
}

/* setReporting(); */ //affichage des erreurs
/* removeMagicQuotes(); */ //deprecated ?
/* unregisterGlobals(); */ //deprecated ?
handleRequest();
