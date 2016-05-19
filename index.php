<?php

define('DS', DIRECTORY_SEPARATOR);
/* define('ROOT', dirname(dirname(__FILE__))); */
define('ROOT', dirname(__FILE__));

/**
 * Get requested url
 */
$APP_URL = $_SERVER['REQUEST_URI'];//$_GET['url'];


//********************************************* TO DELETE
header("Status: 200 OK", false, 200);//TODELETE
/* echo 'SEPARATOR : ' . DS . "<br/>"; */
echo 'PHP SELF : ' . $_SERVER['PHP_SELF'] . '<br/>';
echo 'ROOT : ' . ROOT . "<br/>";
echo 'URL : ' . $APP_URL . "<br/>";//TODELETE
/* echo 'TEST : ' . dirname(__FILE__) . "<br/>"; */
echo 'REQUIRE : ' . ROOT . DS . 'app' . DS . 'kickstart.php' . "<br/>";
//*********************************************

require_once (ROOT . DS . 'app' . DS . 'kickstart.php');
