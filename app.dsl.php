<?php

function app_dir($dir = null) { return $GLOBALS['app']->dir($dir); }

function lib() { return $GLOBALS['app']->lib(func_get_args()); }

function model() { return $GLOBALS['app']->model(func_get_args()); }

function helper() { return $GLOBALS['app']->helper(func_get_args()); }

function module($name) { return $GLOBALS['app']->module($name); }

function action($name, $params = array()) { return $GLOBALS['app']->action($name, $params); }

function start($dir = null) { return $GLOBALS['app']->start($dir); }

function text($text = '') { return $GLOBALS['app']->text($text); }

function view($name, $params = array()) { return $GLOBALS['app']->view($name, $params); }

function layout($name, $content = '') { return $GLOBALS['app']->layout($name, $content); }

function render($name, $params = array()) { return $GLOBALS['app']->render($name, $params); }
