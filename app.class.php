<?php

class app
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
    return isset($this->request) ? $this->request : $this->request = core_object(CORE_DIR . '/request.php');
  }

  public $response;

  function response()
  {
    return isset($this->response) ? $this->response : $this->response = core_object(CORE_DIR . '/response.php');
  }

  public $modules = array();

  function module($name, $callable = null)
  {
    if ($callable) return $this->modules[$name] = $callable;
    if (array_key_exists($name, $this->modules)) {
      $fn = $this->modules[$name];
    } else {
      $fn = include $this->dir() . '/modules/' . $name . '.module.php';
      if (!is_callable($fn)) return $fn;
      $this->modules[$name] = $fn;
    } 
    return $fn();
  }

  public $models = array();

  function model($name)
  {
    if (array_key_exists($name, $this->models)) {
      $class = $this->models[$name];
    } else {
      $fn = include_once $this->dir() . '/models/' . $name . '.model.php';
      $class = $this->models[$name] = $fn();
    }
    return $class;
  }

  function helper($args)
  {
    $helpers = is_array($args) ? $args : func_get_args();
    foreach ($helpers as $name) {
      include_once app_dir() . '/helpers/' . $name . '.helper.php';
    }
    return true;
  }

  function action($name, $params = array())
  {
    extract($params);
    include $this->dir() . '/actions/' . $name . '.action.php';
  }

  public $views = array();

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
    } catch (Exception $e) {
      $res->exception($e);
    }
  }

}
