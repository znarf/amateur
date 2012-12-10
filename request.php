<?php

/* Class */

require_once __DIR__ . '/request.class.php';

/* Instanciate */

$GLOBALS['request'] = $request = new request();

/* DSL */

require __DIR__ . '/request.dsl.php';

/* Return */

return $request;
