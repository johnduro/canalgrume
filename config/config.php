<?php

/**
 * Parse routing.json
 */

//CHECK JSON AND ROUTING VALIDITY !!
//check if path isset else throw error
$content = file_get_contents(ROOT . DS . 'config' . DS . 'routing.json');
$APP_ROUTES = json_decode($content, true);
