<?php

if (empty($app)) {
  require_once __DIR__ . '/app.class.php';
  $GLOBALS['app'] = $app = new \Core\App;
}

foreach (['dir', 'path', 'start'] as $method) {
  replaceable("app_$method", [$app, $method]);
}

foreach (['start', 'model', 'module', 'helper', 'action', 'view', 'layout', 'partial', 'error'] as $method) {
  replaceable($method, [$app, $method]);
}

replaceable('render', function($name, $args = []) use ($app) {
  $app->layout($app->view($name, $args));
});
