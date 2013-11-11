<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

# Namespace

use
amateur\core\app,
amateur\core\core,
amateur\core\request,
amateur\core\response;

require_once amateur_dir . '/core/closure.functions.php';
require_once amateur_dir . '/core/replaceable.functions.php';

# Core

function core($name, $value = null) { return core::instance($name, $value); }

# App

if (empty(core::$app)) {
  core::$app = new app;
}

function app() { return core::$app; }

function start() { return replaceable_call('start', func_get_args(), [core::$app, 'start']); }

function module() { return replaceable_call('module', func_get_args(), [core::$app, 'module']); }

function helper() { return replaceable_call('helper', func_get_args(), [core::$app, 'helper']); }

function model() { return replaceable_call('model', func_get_args(), [core::$app, 'model']); }

function view() { return replaceable_call('view', func_get_args(), [core::$app, 'view']); }

function layout() { return replaceable_call('layout', func_get_args(), [core::$app, 'layout']); }

function error() { return replaceable_call('error', func_get_args(), [core::$app, 'error']); }

# Request

if (empty(core::$request)) {
  core::$request = new request;
}

function request() { return core::$request; }

# Url

function url() { return replaceable_call('url', func_get_args(), [core::$request, 'url']); }

function url_is() { return replaceable_call('url_is', func_get_args(), [core::$request, 'url_is']); }

function url_match() { return replaceable_call('url_match', func_get_args(), [core::$request, 'url_match']); }

function url_start_with() { return replaceable_call('url_start_with', func_get_args(), [core::$request, 'url_start_with']); }

# Params

function has_param() { return replaceable_call('has_param', func_get_args(), [core::$request, 'has_param']); }

function get_param() { return replaceable_call('get_param', func_get_args(), [core::$request, 'get_param']); }

function get_int() { return replaceable_call('get_int', func_get_args(), [core::$request, 'get_int']); }

function get_bool() { return replaceable_call('get_bool', func_get_args(), [core::$request, 'get_bool']); }

function set_param() { return replaceable_call('set_param', func_get_args(), [core::$request, 'set_param']); }

# Methods

function is_get() { return replaceable_call('is_get', func_get_args(), [core::$request, 'is_get']); }

function is_post() { return replaceable_call('is_post', func_get_args(), [core::$request, 'is_post']); }

function is_put() { return replaceable_call('is_put', func_get_args(), [core::$request, 'is_put']); }

function is_delete() { return replaceable_call('is_delete', func_get_args(), [core::$request, 'is_delete']); }

function is_write() { return replaceable_call('is_write', func_get_args(), [core::$request, 'is_write']); }

function check_method() { return replaceable_call('check_method', func_get_args(), [core::$request, 'check_method']); }

function check_parameters() { return replaceable_call('check_parameters', func_get_args(), [core::$request, 'check_parameters']); }

# Response

if (empty(core::$response)) {
  core::$response = new response;
}

function response() { return core::$response; }

function status() { return replaceable_call('status', func_get_args(), [core::$response, 'status']); }

function set_header() { return replaceable_call('set_header', func_get_args(), [core::$response, 'set_header']); }

function render() { return replaceable_call('render', func_get_args(), [core::$response, 'render']); }

function redirect() { return replaceable_call('redirect', func_get_args(), [core::$response, 'redirect']); }
