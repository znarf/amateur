<?php namespace Amateur\Core;

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

  function request()
  {
    return core('request');
  }

  function response()
  {
    return core('response');
  }

  # Files

  function filename($type, $name)
  {
    $folder = "{$type}s";
    return $this->dir() . "/{$folder}/{$name}.{$type}.php";
  }

  # Modules

  public $modules = [];

  function module($name, $args = [])
  {
    # Set module
    if (is_callable($args)) {
      return $this->modules[$name] = $args;
    }
    $app = $this;
    $req = $this->request();
    $res = $this->response();
    if (isset($this->modules[$name]) || array_key_exists($name, $this->modules)) {
      $module = $this->modules[$name];
      return $module($req, $res);
    }
    else {
      $module = include $this->filename('module', $name);
      if (is_callable($module)) {
        $this->modules[$name] = $module;
        return $module($req, $res);
      }
    }
  }

  # Models

  public $models = [];

  function model($name)
  {
    if ($name === (array)$name) {
      $_models = [];
      foreach ($name as $_name) $_models[] = $this->model($_name);
      return $_models;
    }
    if (isset($this->models[$name]) || array_key_exists($name, $this->models)) {
      $model = $this->models[$name];
    }
    else {
      $this->models[$name] = $model = include $this->filename('model', $name);
      # If no model returned (object or callable), we guess the classname and instanciate it
      if (!is_object($model) && !is_callable($model)) {
        $classname = ucfirst($name);
        $this->models[$name] = $model = new $classname();
      }
    }
    return is_callable($model) ? $model() : $model;
  }

  # Helpers

  public $helpers = [];

  function helper($name)
  {
    if ($name === (array)$name) {
      $_helpers = [];
      foreach ($name as $_name) $_helpers[] = $this->helper($_name);
      return $_helpers;
    }
    if (isset($this->helpers[$name]) || array_key_exists($name, $this->helpers)) {
      $helper = $this->helpers[$name];
    }
    else {
      $this->helpers[$name] = $helper = include $this->filename('helper', $name);
    }
    return is_callable($helper) ? $helper() : $helper;
  }

  # Views

  public $views = [];

  function view($name, $args = [])
  {
    # Set view
    if (is_callable($args)) {
      return $this->views[$name] = $args;
    }
    # Function view
    if (array_key_exists($name, $this->views)) {
      ob_start();
      $this->views[$name]($args);
      return ob_get_clean();
    }
    # Include view
    $template =  $this->filename('view', $name);
    if (file_exists($template)) {
      ob_start();
      extract($args);
      include $template;
      return ob_get_clean();
    }
  }

  # Layouts

  function layout($content = '', $name = 'default')
  {
    include $this->filename('layout', $name);
  }

  function start($dir = null)
  {
    $app = $this;
    $req = $this->request();
    $res = $this->response();
    try {
      $start = include $this->dir($dir) . '/app.start.php';
      if (is_callable($start)) $start($req, $res);
    }
    catch (\HttpException $e) {
      ob_end_clean();
      $this->error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
    }
    catch (\Exception $e) {
      ob_end_clean();
      $this->error(500, $e->getMessage(), $e->getTraceAsString());
    }
  }

  function error($code = 500, $message = 'Application Error', $trace = '')
  {
    $this->response()->status($code);
    # Try error views
    foreach ([$code, 'error'] as $view) {
      if ($result = $this->view($view, compact('code', 'message', 'trace'))) break;
    }
    $this->layout( isset($result) ? $result : "<h2>{$code} {$message}</h2>" . "<pre>{$trace}</pre>" );
  }

}
