<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

# Autoload (if not already available)
if (!class_exists('\amateur\amateur')) {
  require_once amateur_dir . '/classes/loader.php';
  \amateur\loader::register_namespace('amateur', amateur_dir . '/classes');
}

# Replaceables (if not already available)
if (!function_exists('\amateur\amateur')) {
  \amateur\replaceable::load(amateur_dir . '/replaceables', 'amateur');
}
