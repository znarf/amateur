<?php

/* Class */

require_once __DIR__ . '/response.class.php';

/* Instanciate */

$GLOBALS['response'] = $response = new response();

/* DSL */

require_once __DIR__ . '/response.dsl.php';

/* Return */

return $response;
