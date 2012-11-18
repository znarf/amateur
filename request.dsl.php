<?php

/* Replaceables */

replaceable('request_method', function() {
  return $_SERVER['REQUEST_METHOD'];
});

replaceable('request_url', function() {
  return strtok($_SERVER['REQUEST_URI'], '?');
});

replaceable('request_host', function() {
  return $_SERVER['HTTP_HOST'];
});

replaceable('request_param', function($name, $value = null) {
  if ($value !== null) return $_REQUEST[$name] = $value;
  if (isset($_REQUEST[$name])) return $_REQUEST[$name];
});

replaceable('request_header', function($name) {
  static $headers = array();
  if (array_key_exists($name, $headers)) {
    return $headers[$name];
  } else {
    $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));
    return $headers[$name] = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
  }
});

/* Helpers */

function host($value = null) { return request_host($value); }

function has_param() { return request_param($name) ? true : false; }

function get_param($name, $default = null) { $value = request_param($name); return $value ? $value : $default; }

function set_param($name, $value) { return request_param($name, $value); }

function is_get() { return request_method() == 'GET'; }

function is_post() { return request_method() == 'POST'; }

function is_patch() { return request_method() == 'PATCH'; }

function is_put() { return request_method() == 'PUT'; }

function is_delete() { return request_method() == 'DELETE'; }

function is_write() { return in_array(request_method(), array('POST', 'PATCH', 'PUT', 'DELETE')); }
