<?php

defined('amateur_dir') || define('amateur_dir', __DIR__);

require_once amateur_dir . '/core/core.functions.php';

# Core Objects

$app = core('app');

$request = $app->request();

$response = $app->response();

# App

function app() { global $app; return $app; }

function start($dir = null) { global $app; return $app->start($dir); }

function module($name, $callable = null) { global $app; return $app->module($name, $callable); }

function helper($name) { global $app; return $app->helper($name); }

function model($name) { global $app; return $app->model($name); }

function view($name, $args = []) { global $app; return $app->view($name, $args); }

function partial($name, $args = []) { global $app; return $app->partial($name, $args); }

function layout($content = '', $name = 'default') { global $app; return $app->layout($content, $name); }

# Request

function request() { global $request; return $request; }

function url($value = null) { global $request; return $request->url($value); }

function url_is($str) { global $request; return $request->url_is($str); }

function url_start_with($str) { global $request; return $request->url_start_with($str); }

function url_match($route) { global $request; return $request->url_match($route); }

function set_param($name, $value) { global $request; return $request->param($name, $value); }

function has_param($name) { global $request; return $request->param($name) ? true : false; }

function get_param($name, $default = null) { global $request; $value = $request->param($name); return isset($value) ? $value : $default; }

function get_int($name, $default = null) { global $request; $value = $request->param($name); return isset($value) ? (int)$value : $default; }

function get_bool($name, $default = null) { global $request; $value = $request->param($name); return isset($value) ? $request->boolise($value) : $default; }

function request_host() { global $request; return $request->host(); }

function request_method() { global $request; return $request->method(); }

function request_header($name) { global $request; return $request->header($name); }

function request_url() { global $request; return $request->url(); }

function is_get() { global $request; return $request->method() == 'GET'; }

function is_post() { global $request; return $request->method() == 'POST'; }

function is_put() { global $request; return $request->method() == 'PUT'; }

function is_delete() { global $request; return $request->method() == 'DELETE'; }

function is_ajax() { global $request; return $request->header('X-Requested-With') == 'XMLHttpRequest'; }

function is_write() { global $request; return in_array($request->method(), ['POST', 'PATCH', 'PUT', 'DELETE']); }

function check_method($methods) { global $request; return $request->check_method($methods); }

function check_parameters($parameters) { global $request; return $request->check_parameters($parameters); }

# Response

function response() { global $response; return $response; }

function status($code) { global $response; return $response->status($code); }

function set_header($name, $value) { global $response; return $response->set_header($name, $value); }

function render($name, $args = []) { global $response; return $response->render($name, $args); }

function redirect($path, $permanent = false) { global $response; return $response->redirect($path, $permanent); }

# Errors

function error($code = 500, $message = 'Application Error') { global $app; return $app->error($code, $message); }

function not_found($message = 'Not Found') { global $app; return $app->error(404, $message); }

function unknown_url() { global $app, $request; return $app->error(404, sprintf("No url match '%s'.", $request->url())); }

# Mixed

function current_url() { global $app, $request; return $request->protocol() . '://' . $request->host() . $app->path() . $request->url(); }

function relative_url($path = '') { global $app, $request; return $app->path() . $path; }

function absolute_url($path = '') { global $app, $request; return $request->protocol() . '://' . $request->host() . $app->path() . $path; }
