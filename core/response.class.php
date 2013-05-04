<?php

namespace Amateur\Core;

class Response
{

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
    $app = core_object('app');
    $url = strpos($path, '://') !== false ? $path : $app->path() . $path;
    $this->status($permanent ? 301 : 302);
    $this->set_header('Location', $url);
    exit;
  }

  function render($name, $args = [])
  {
    $app = core_object('app');
    $app->layout($app->view($name, $args));
    exit;
  }

}
