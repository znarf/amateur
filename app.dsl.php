<?php

if (empty($app)) {
  require_once __DIR__ . '/app.class.php';
  $GLOBALS['app'] = $app = new app();
}

replaceable('app_dir', function($value = null) use($app) {
  return $app->dir($value);
});

replaceable('app_path', function($value = null) use($app) {
  return $app->path($value);
});

replaceable('app_start', function($value = null) use($app) {
  return $app->start($value);
});

replaceable('model', function($name) use($app) {
  return $app->model($name);
});

replaceable('module', function($name, $callable = null) use($app) {
  return $app->module($name, $callable);
});

replaceable('helper', function($args) use($app) {
  $helpers = is_array($args) ? $args : func_get_args();
  return $app->helper($helpers);
});

replaceable('action', function($name, $params = []) use($app) {
  return $app->action($name, $params);
});

replaceable('view', function($name, $params = []) use($app) {
  return $app->view($name, $params);
});

replaceable('render', function($name, $params = []) use($app) {
  return $app->layout('default', $app->view($name, $params));
});
