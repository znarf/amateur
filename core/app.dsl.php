<?php

if (empty($app)) {
  require_once __DIR__ . '/app.class.php';
  $GLOBALS['app'] = $app = new \Amateur\Core\App;
}

foreach (['dir', 'path', 'start'] as $method) {
  replaceable("app_$method", [$app, $method]);
}

foreach (['start', 'model', 'module', 'helper', 'action', 'view', 'layout', 'partial', 'error'] as $method) {
  replaceable($method, [$app, $method]);
}

replaceable('render', function($name, $args = []) use ($app) {
  $app->layout($app->view($name, $args));
  exit;
});

# Errors

replaceable('not_found', function($message = 'Not Found') use ($app) {
  $app->error(404, $message);
});

replaceable('unknown_url', function() use ($app) {
  $app->error(404, sprintf("No url match '%s'.", $app->request()->url()));
});

# Url

replaceable('absolute_url', function($path = '') use($app) {
  return 'http://' . $app->request()->host() . $app->path() . $path;
});

replaceable('static_url', function($path = '') use($app) {
  return '//' . $app->request()->host() . $app->path() . $path;
});

replaceable('current_url', function() use($app) {
  return 'http://' . $app->request()->host() . $app->path() . $app->request->url();
});

replaceable('relative_url', function($path = '') use($app) {
  return $app->path() . $path;
});
