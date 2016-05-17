<?php

/**
 * Parse routing.json
 */
$content = file_get_contents(ROOT . DS . 'config' . DS . 'routing.json');
$APP_ROUTES = json_decode($content);
