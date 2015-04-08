<?php

namespace amateur
{

use amateur\core\amateur;

function url_match($route)
{
  $route = "^$route$";
  $route = str_replace('/', '\/', $route);
  $route = str_replace('*', '([^\/]+)', $route);
  $result = preg_match("/$route/", amateur::request_url(), $matches);
  return $result ? $matches : false;
}

}
