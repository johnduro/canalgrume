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
     * @var $APP_URL
     * Url of the request
     */
    global $APP_URL;

    /**
     * @var $APP_ROUTES
     * Routes defined by user
     */
    global $APP_ROUTES;

    /**
     * @var $APP_VIEWS
     * Views defined by user
     */
    global $APP_VIEWS;

    /**
     * App Object
     * - Template
     */
    $app = new AppCore($APP_URL, $APP_ROUTES, $APP_VIEWS);

    /* echo '<pre>'; */
    /* var_dump($APP_VIEWS); */
    /* echo '</pre>'; */

    $requestHandler = new RequestHandler($app);
    $requestHandler->treatRequest();
}

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
/* setReporting(); */ //affichage des erreurs
/* removeMagicQuotes(); */ //deprecated ?
/* unregisterGlobals(); */ //deprecated ?


handleRequest();
