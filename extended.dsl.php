<?php

use
amateur\core\app,
amateur\core\core,
amateur\core\request,
amateur\core\response;

# Errors

class http_exception extends exception {}

function http_error($code, $message) { return new http_exception($message, $code); }

function not_found($message = 'Not Found') { return error(404, $message); }

function unknown_url() { return error(404, sprintf("No url match '%s'.", url())); }

# Request

function referer()
{
  static $default;
  $default || $default = function() { return (string)core::$request->header('Referer'); };
  return replaceable_call('referer', func_get_args(), $default);
}

function is_ajax()
{
  static $default;
  $default || $default = function() { return core::$request->header('X-Requested-With') == 'XMLHttpRequest'; };
  return replaceable_call('is_ajax', func_get_args(), $default);
}

# Url

function current_url()
{
  static $default;
  $default || $default = function() {
    return core::$request->protocol() . '://' . core::$request->host() . core::$app->path() . core::$request->url();
  };
  return replaceable_call('current_url', func_get_args(), $default);
}

function relative_url()
{
  static $default;
  $default || $default = function($path = '') {
    return core::$app->path() . $path;
  };
  return replaceable_call('relative_url', func_get_args(), $default);
}

function absolute_url()
{
  static $default;
  $default || $default = function($path = '') {
    return core::$request->protocol() . '://' . core::$request->host() . core::$app->path() . $path;
  };
  return replaceable_call('absolute_url', func_get_args(), $default);
}

function static_url()
{
  static $default;
  $default || $default = function($path = '') {
    return '//' . core::$request->host() . core::$app->path() . $path;
  };
  return replaceable_call('static_url', func_get_args(), $default);
}
