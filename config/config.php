<?php

/**
 * Parse routing.json
 */
//CHECK JSON AND ROUTING VALIDITY !!
//check if path isset else throw error
$routingJSON = file_get_contents(ROOT . DS . 'config' . DS . 'routing.json');
$APP_ROUTES = json_decode($routingJSON, true);

/**
 * Parse views.json
 */
//CHECK JSON AND VIEWS VALIDITY !!
//ADD default views : 404, 403, template_not_found
$viewsJSON = file_get_contents(ROOT . DS . 'config' . DS . 'views.json');
$APP_VIEWS = json_decode($viewsJSON, true);
// var_dump(json_last_error());
