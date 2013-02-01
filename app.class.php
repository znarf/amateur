<?php

namespace Core;

class App
{

  public $dir;

  function dir($value = null)
  {
    return isset($value) ? $this->dir = realpath($value) : $this->dir;
  }

  public $path;

  function path($value = null)
  {
    return isset($value) ? $this->path = $value : $this->path;
  }

  public $request;

  function request()
  {
    return isset($this->request) ? $this->request : $this->request = core_object(core_dir . '/request.php');
  }

  public $response;

  function response()
  {
    return isset($this->response) ? $this->response : $this->response = core_object(core_dir . '/response.php');
  }

  public $modules = [];

  function module($name, $callable = null)
  {
    if ($callable) return $this->modules[$name] = $callable;
    $app = $this;
    $req = $this->request();
    $res = $this->response();
    if (array_key_exists($name, $this->modules)) {
      $fn = $this->modules[$name];
    } else {
      $fn = include $this->dir() . '/modules/' . $name . '.module.php';
      if (!is_callable($fn)) return $fn;
      $this->modules[$name] = $fn;
    }
    return $fn($req, $res);
  }

  public $models = [];

  function model($name)
  {
    if (is_array($name)) {
      $_models = [];
      foreach ($name as $_name) $_models[] = self::model($_name);
      return $_models;
    }
    if (array_key_exists($name, $this->models)) {
      return $this->models[$name];
    } else {
      include_once $this->dir() . '/models/' . $name . '.model.php';
      $classname = ucfirst($name);
      return $this->models[$name] = new $classname();
    }
  }

  function helper($args)
  {
    $helpers = is_array($args) ? $args : func_get_args();
    foreach ($helpers as $name) {
      include_once $this->dir() . '/helpers/' . $name . '.helper.php';
    }
    return true;
  }

  function action($name, $params = [])
  {
    extract($params);
    include $this->dir() . '/actions/' . $name . '.action.php';
  }

  function partial($name, $params = [])
  {
    extract($params);
    include $this->dir() . '/partials/' . $name . '.partial.php';
  }

  public $views = [];

  function view($name, $args = null)
  {
    if (is_callable($args)) return $this->views[$name] = $args;
    // Start Template
    ob_start();
    // Function view
    if (array_key_exists($name, $this->views)) {
      $this->views[$name]($args);
    // Include view
    } else {
      if (is_array($args)) extract($args);
      include $this->dir() . '/views/' . $name . '.view.php';
    }
    // Return
    return ob_get_clean();
  }

  function layout($name, $content = '')
  {
    include $this->dir() . '/layouts/' . $name . '.layout.php';
  }

  function start($dir = null)
  {
    $app = $this;
    $req = $this->request();
    $res = $this->response();
    try {
      $start = include $this->dir($dir) . '/app.start.php';
      if (is_callable($start)) $start($req, $res);
    } catch (\Exception $e) {
      $res->exception($e);
    }
  }

}
