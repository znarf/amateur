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
      foreach ($name as $_name) $_models[] = $this->model($_name);
      return $_models;
    }
    if (array_key_exists($name, $this->models)) {
      return $this->models[$name];
    } else {
      $fn = include $this->dir() . '/models/' . $name . '.model.php';
      if (is_callable($fn)) return $this->models[$name] = $fn();
      $classname = ucfirst($name);
      return $this->models[$name] = new $classname();
    }
  }

  public $helpers = [];

  function helper($name)
  {
    if (is_array($name)) {
      $_helpers = [];
      foreach ($name as $_name) $_helpers[] = $this->helper($_name);
      return $_helpers;
    }
    if (array_key_exists($name, $this->helpers)) {
      return $this->helpers[$name];
    }
    $result = include $this->dir() . '/helpers/' . $name . '.helper.php';
    return $this->helpers[$name] = is_object($result) ? $result : null;
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

  function view($name, $args = [])
  {
    // Set view
    if (is_callable($args)) {
      return $this->views[$name] = $args;
    }
    // Start Template
    ob_start();
    // Function view
    if (array_key_exists($name, $this->views)) {
      $this->views[$name]($args);
      return ob_get_clean();
    }
    // Include view
    $filename = $this->dir() . '/views/' . $name . '.view.php';
    if (file_exists($filename)) {
      extract($args);
      include $filename;
      return ob_get_clean();
    }
  }

  function layout($content = '', $name = 'default')
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
    } catch (\HttpException $e) {
      $this->error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
    } catch (\Exception $e) {
      $this->error(500, $e->getMessage(), $e->getTraceAsString());
    }
  }

  function error($code = 500, $message = 'Application Error', $trace = '')
  {
    $this->response()->status($code);
    // Try error views
    foreach ([$code, 'error'] as $view) {
      if ($result = $this->view($view, compact('code', 'message', 'trace'))) break;
    }
    $this->layout( isset($result) ? $result : "<h2>{$code} {$message}</h2>" . "<pre>{$trace}</pre>" );
  }

}
