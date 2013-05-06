<?php

namespace Amateur\Core;

class Response
{

  function app()
  {
    return core('app');
  }

  function status($code)
  {
    http_response_code($code);
  }

  function set_header($name, $value)
  {
    header("$name:$value");
  }

  function redirect($path, $permanent = false)
  {
    $url = strpos($path, '://') !== false ? $path : $this->app()->path() . $path;
    $this->status($permanent ? 301 : 302);
    $this->set_header('Location', $url);
    exit;
  }

  function render($name, $args = [])
  {
    $app = $this->app();
    $app->layout($app->view($name, $args));
    exit;
  }

}
