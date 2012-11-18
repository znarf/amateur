<?php

function app_dir($value = null)
{
  static $app_dir;
  return isset($value) ? $app_dir = realpath($value) : $app_dir;
}

function lib()
{
  foreach (func_get_args() as $name) {
    include_once app_dir() . '/lib/' . $name . '.lib.php';
  }
}

/* Model */

function model()
{
  foreach (func_get_args() as $name) {
    include_once app_dir() . '/models/' . $name . '.model.php';
  }
}

/* Controller */

function module($name, $callable = null)
{
  static $modules = array();
  if ($callable) return $modules[$name] = $callable;
  if (array_key_exists($name, $modules)) return $modules[$name]();
  return include app_dir() . '/modules/' . $name . '.module.php';
}

function action($name, $params = array())
{
  extract($params);
  include app_dir() . '/actions/' . $name . '.action.php';
}

function helper()
{
  foreach (func_get_args() as $name) {
    include_once app_dir() . '/helpers/' . $name . '.helper.php';
  }
}

/* Views */

function view($name, $params = array())
{
  extract($params);
  ob_start();
  include app_dir() . '/views/' . $name . '.view.php';
  return ob_get_clean();
}

function layout($name, $content = '')
{
  include app_dir() . '/layouts/' . $name . '.layout.php';
}

function render($name, $params = array())
{
  layout('default', view($name, $params));
}

function text($text = '')
{
  echo htmlspecialchars($text);
}

/* Start */

function start($dir = null)
{
  include app_dir($dir) . '/app.start.php';
}
