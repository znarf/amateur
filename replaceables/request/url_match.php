<?php

return function($route) {
  $route = "^$route$";
  $route = str_replace('/', '\/', $route);
  $route = str_replace('*', '([^\/]+)', $route);
  $result = preg_match("/$route/", request_url(), $matches);
  return $result ? $matches : false;
};
