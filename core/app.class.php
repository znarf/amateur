<?php namespace amateur\core;

use
exception,
http_exception;

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

  public $namespace;

  function ns($value = null)
  {
    return isset($value) ? $this->namespace = $value : $this->namespace;
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
    return $this->dir . "/{$folder}/{$name}.{$type}.php";
  }

  # Modules

  public $modules = [];

  function module($name, $callable = null)
  {
    # Set module
    if (isset($callable) && is_callable($callable)) {
      return $this->modules[$name] = $callable;
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

  function model($name, $value = null)
  {
    # Set
    if (isset($value)) {
      return $this->models[$name] = $value;
    }
    # Multi
    if ($name === (array)$name) {
      return array_map([$this, 'model'], $name);
    }
    # Loaded
    if (isset($this->models[$name]) || array_key_exists($name, $this->models)) {
      $model = $this->models[$name];
    }
    # Load
    else {
      $this->models[$name] = $model = include $this->filename('model', $name);
    }
    return $model;
  }

  # Helpers

  public $helpers = [];

  function helper($name, $value = null)
  {
    # Set
    if (isset($value)) {
      return $this->helpers[$name] = $value;
    }
    # Multi
    if ($name === (array)$name) {
      return array_map([$this, 'helper'], $name);
    }
    # Loaded
    if (isset($this->helpers[$name]) || array_key_exists($name, $this->helpers)) {
      $helper = $this->helpers[$name];
    }
    # Load
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
    if (isset($this->views[$name]) || array_key_exists($name, $this->views)) {
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
    # Start
    $app = $this;
    $req = $this->request();
    $res = $this->response();
    try {
      $start = include $this->dir($dir) . '/app.start.php';
      if (is_callable($start)) $start($req, $res);
    }
    catch (http_exception $e) {
      ob_end_clean();
      $this->error($e->getCode(), $e->getMessage(), $e->getTraceAsString());
    }
    catch (exception $e) {
      ob_end_clean();
      $this->error(500, $e->getMessage(), $e->getTraceAsString());
    }
  }

  function end()
  {
    if (function_exists('fastcgi_finish_request')) {
      fastcgi_finish_request();
    }
    else {
      flush();
    }
  }

  # Autoload

  function register_autoload()
  {
    spl_autoload_register(function($classname) {
      # Remove leading \ if any
      $classname = ltrim($classname, '\\');
      # Then if class match namespace\type\name pattern
      if (preg_match("/{$this->namespace}\\\([^\\\]+)\\\([^\\\]+)/", $classname, $matches)) {
        $filename = $this->filename($matches[1], $matches[2]);
        if (file_exists($filename)) {
          require $filename;
        }
      }
    });
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
