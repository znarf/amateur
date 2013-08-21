<?php

namespace Amateur\Core;

class Request
{

  public $url;

  public $headers = [];

  function app()
  {
    return core('app');
  }

  function url($value = null)
  {
    if (isset($value)) {
      return $this->url = $value;
    }
    elseif (!isset($this->url)) {
      $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
      $this->url = str_replace($this->app()->path(), '', $request_uri);
    }
    return $this->url;
  }

  function method()
  {
    return isset($_REQUEST['forceMethod']) ? $_REQUEST['forceMethod'] : $_SERVER['REQUEST_METHOD'];
  }

  function host()
  {
    return $_SERVER['HTTP_HOST'];
  }

  function protocol()
  {
    return 'http';
  }

  function param($name, $value = null)
  {
    if (isset($value)) {
      return $_REQUEST[$name] = $value;
    }
    elseif (isset($_REQUEST[$name])) {
      return $_REQUEST[$name];
    }
  }

  function boolise($value)
  {
    if (is_string($value) && strtolower($value) == 'true') {
      return true;
    }
    elseif (is_string($value) && strtolower($value) == 'false') {
      return false;
    }
    else {
      return (bool)$value;
    }
  }

  function header($name)
  {
    if (array_key_exists($name, $this->headers)) {
      return $this->headers[$name];
    }
    else {
      $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
      return $this->headers[$name] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
    }
  }

  function url_is($str)
  {
    return $str == $this->url();
  }

  function url_start_with($str)
  {
    return strpos($this->url(), $str) === 0;
  }

  function url_match($route)
  {
    $route = "^$route$";
    $route = str_replace('/', '\/', $route);
    $route = str_replace('*', '([^\/]+)', $route);
    $result = preg_match("/$route/", $this->url(), $matches);
    return $result ? $matches : false;
  }

  function check_method($methods)
  {
    $methods = is_string($methods) ? explode(",", strtoupper($methods)) : $methods;
    if (!in_array($this->method(), $methods)) {
      throw http_error(405, 'Method Not Allowed');
    }
  }

  function check_parameters($parameters)
  {
    $parameters = is_string($parameters) ? explode(',', $parameters) : $parameters;
    foreach ($parameters as $name) {
      if ($this->param($name) === null) {
        throw http_error(400, "Missing Parameter ($name)");
      }
    }
  }

}
