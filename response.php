<?php

/* Class */

require_once __DIR__ . '/core/response.class.php';

/* Instanciate */

$GLOBALS['response'] = $response = new \Amateur\Core\Response;

/* DSL */

require __DIR__ . '/core/response.dsl.php';

/* Return */

return $response;
