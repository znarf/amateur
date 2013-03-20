<?php

/* Class */

require_once __DIR__ . '/app.class.php';

/* Instanciate */

$GLOBALS['app'] = $app = new \Core\App;

/* DSL */

require __DIR__ . '/app.dsl.php';

/* Return */

return $app;
