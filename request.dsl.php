<?php

function request_host($value = null) { return request::host($value); }

function request_method($value = null) { return request::method($value); }

function request_header($value = null) { return request::header($value); }

function has_param($name) { return request::param($name) ? true : false; }

function get_param($name, $default = null) { $value = request::param($name); return $value ? $value : $default; }

function get_int($name, $default = null) { return (int)get_param($name, $default); }

function set_param($name, $value) { return request::param($name, $value); }

function is_get() { return request::method() == 'GET'; }

function is_post() { return request::method() == 'POST'; }

function is_patch() { return request::method() == 'PATCH'; }

function is_put() { return reques::method() == 'PUT'; }

function is_delete() { return request::method() == 'DELETE'; }

function is_write() { return in_array(request::method(), array('POST', 'PATCH', 'PUT', 'DELETE')); }

function url_match($route) { return request::url_match($route); }

function url_is($str) { return request::url_is($str); }

function url_start_with($str) { return request::url_start_with($str); }
