<?php

/* Class */

require_once __DIR__ . '/core/app.class.php';

/* Instanciate */

$GLOBALS['app'] = $app = new \Amateur\Core\App;

/* DSL */

require __DIR__ . '/core/app.dsl.php';

/* Return */

return $app;
