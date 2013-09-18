<?php namespace amateur\core;

class response
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
    $url = strpos($path, '://') !== false ? $path : core('app')->path() . $path;
    $this->status($permanent ? 301 : 302);
    $this->set_header('Location', $url);
    exit;
  }

  function render($name, $args = [])
  {
    $app = core('app');
    $app->layout($app->view($name, $args));
    exit;
  }

}
