<?php

defined('amateur_dir') || define('amateur_dir', __dir__);

# Autoload
if (!class_exists('\amateur\core\loader')) {
  require_once amateur_dir . '/classes/core/loader.php';
  \amateur\core\loader::register_namespace('amateur', amateur_dir . '/classes');
}

# Replaceables
\amateur\core\replaceable::instance()->load(amateur_dir . '/replaceables');
