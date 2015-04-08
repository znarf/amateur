<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

# Autoload (if not already available)
if (!class_exists('\amateur\core\amateur')) {
  require_once amateur_dir . '/classes/core/loader.php';
  \amateur\core\loader::register_namespace('amateur', amateur_dir . '/classes');
}

# Replaceables (if not already available)
if (!function_exists('\amateur\amateur')) {
  \amateur\core\replaceable::load(amateur_dir . '/replaceables', 'amateur');
}
