<?php

function url() { return $GLOBALS['url']->get(); }

function url_is($string) { return $GLOBALS['url']->is($string); }

function url_start_with($string) { return $GLOBALS['url']->start_with($string); }

function url_match($route, &$matches) { return $GLOBALS['url']->match($route, $matches); }

function base_path($value = null) { return $GLOBALS['url']->base_path($value); }

// function host() { return $_SERVER['HTTP_HOST']; }

function base_url() { return 'http://' . host() . base_path(); }

// replaceable('host', array('Core\Request', 'host'));

// function host($arg = null)
// {
//   static $func = null;
//   if (is_callable($arg)) {
//     $func = $arg;
//     return $func;
//   }
//   if ($func) return call_user_func_array($func, func_get_args());
//   return $_SERVER['HTTP_HOST'];
// }
