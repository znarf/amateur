<?php

/* Class */

require_once __DIR__ . '/core/request.class.php';

/* Instanciate */

$GLOBALS['request'] = $request = new \Amateur\Core\Request;

/* DSL */

require __DIR__ . '/core/request.dsl.php';

/* Return */

return $request;
