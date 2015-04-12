<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

# Autoload Classes (if not already available)
if (!class_exists('\amateur\amateur')) {
  require_once amateur_dir . '/classes/loader.php';
  \amateur\loader::register_namespace('amateur', amateur_dir . '/classes');
}

# Load Replaceables
\amateur\replaceable::load(amateur_dir . '/replaceables', 'amateur');
