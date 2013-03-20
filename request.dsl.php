<?php

if (empty($request)) {
  require_once __DIR__ . '/request.class.php';
  $GLOBALS['request'] = $request = new request();
}

foreach (['host', 'method', 'header'] as $method) {
  replaceable("request_$method", [$request, $method]);
}

foreach (['url', 'url_match', 'url_is', 'url_start_with'] as $method) {
  replaceable($method, [$request, $method]);
}

/* Methods */

foreach (['get', 'post', 'patch', 'put', 'delete'] as $method) {
  replaceable("is_$method", function() use($request, $method) {
    return $request->method() == strtoupper($method);
  });
}

replaceable("is_write", function() use($request) {
  return in_array($request->method(), ['POST', 'PATCH', 'PUT', 'DELETE']);
});

replaceable('check_method', [$request, 'check_method']);

/* Params */

replaceable("has_param", function($name) use($request) {
  return $request->param($name) ? true : false;
});

replaceable("set_param", function($name, $value) use($request) {
  return $request->param($name, $value);
});

replaceable("get_param", function($name, $default = null) use($request) {
  $value = $request->param($name);
  return $value ? $value : $default;
});

replaceable("get_int", function($name, $default = null) use($request) {
  $value = $request->param($name);
  return $value ? (int)$value : $default;
});

replaceable("get_bool", function($name, $default = null) use($request) {
  $value = $request->param($name);
  return $value ? (bool)$value : $default;
});

replaceable('check_parameters', [$request, 'check_parameters']);
