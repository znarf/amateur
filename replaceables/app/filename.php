<?php

return function($type, $name) {
  $folder = "{$type}s";
  return app_dir() . "/{$folder}/{$name}.{$type}.php";
};
