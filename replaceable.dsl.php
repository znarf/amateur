<?php

defined('amateur_dir') || define('amateur_dir', __DIR__);

require_once amateur_dir . '/core/core.functions.php';

require_once amateur_dir . '/core/replaceable.functions.php';

$app = core_object('app');

$request = core_object('request');

$response = core_object('response');

# App
# ---

foreach (['dir', 'path', 'start'] as $method) {
  replaceable("app_$method", [$app, $method]);
}

foreach (['start', 'model', 'module', 'helper', 'view', 'layout', 'error'] as $method) {
  replaceable($method, [$app, $method]);
}

# Errors

replaceable('not_found', function($message = 'Not Found') use ($app) {
  $app->error(404, $message);
});

replaceable('unknown_url', function() use ($app) {
  $app->error(404, sprintf("No url match '%s'.", $app->request()->url()));
});

# Url

replaceable('absolute_url', function($path = '') use($app) {
  return 'http://' . $app->request()->host() . $app->path() . $path;
});

replaceable('static_url', function($path = '') use($app) {
  return '//' . $app->request()->host() . $app->path() . $path;
});

replaceable('current_url', function() use($app) {
  return 'http://' . $app->request()->host() . $app->path() . $app->request->url();
});

replaceable('relative_url', function($path = '') use($app) {
  return $app->path() . $path;
});

# Request
# -------

foreach (['host', 'method', 'header'] as $method) {
  replaceable("request_$method", [$request, $method]);
}

foreach (['url', 'url_match', 'url_is', 'url_start_with'] as $method) {
  replaceable($method, [$request, $method]);
}

# Headers

replaceable('is_ajax', function() use($request) {
  return $request->header('X-Requested-With') == 'XMLHttpRequest';
});

replaceable('referer', function($default = null) use($request) {
  $referer = $request->header('Referer');
  return empty($referer) ? $default : $referer;
});

# Methods

foreach (['get', 'post', 'patch', 'put', 'delete'] as $method) {
  replaceable("is_$method", function() use($request, $method) {
    return $request->method() == strtoupper($method);
  });
}

replaceable('is_write', function() use($request) {
  return in_array($request->method(), ['POST', 'PATCH', 'PUT', 'DELETE']);
});

replaceable('check_method', [$request, 'check_method']);

# Parameters

replaceable('has_param', function($name) use($request) {
  return $request->param($name) ? true : false;
});

replaceable('set_param', function($name, $value) use($request) {
  return $request->param($name, $value);
});

replaceable('get_param', function($name, $default = null) use($request) {
  $value = $request->param($name);
  return isset($value) ? $value : $default;
});

replaceable('get_int', function($name, $default = null) use($request) {
  $value = $request->param($name);
  return isset($value) ? (int)$value : $default;
});

replaceable('get_bool', function($name, $default = null) use($request) {
  $value = $request->param($name);
  return isset($value) ? $request->boolise($value) : $default;
});

replaceable('check_parameters', [$request, 'check_parameters']);

# Response
# --------

foreach (['status', 'set_header', 'redirect', 'render'] as $method) {
  replaceable($method, [$response, $method]);
}
