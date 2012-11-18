<?php

/* Functions */

function url($value = null)
{
  static $url;
  if ($value) $url = $value;
  return $url ? $url : $url = request_url();
}

function base_path($value = null)
{
  static $base_path;
  if ($value) {
    $base_path = $value;
    url(str_replace($value, '', url()));
  }
  return $base_path ? $base_path : '';
}

function url_match($route, &$matches)
{
  $route = "^$route$";
  $route = str_replace('/', '\/', $route);
  $route = str_replace('*', '([^\/]+)', $route);
  return preg_match("/$route/", url(), $matches);
}

/* Helpers */

function url_is($str)
{
  return $str == url();
}

function url_start_with($str)
{
  return strpos(url(), $str) === 0;
}

function base_url()
{
  return 'http://' . request_host() . base_path();
}
