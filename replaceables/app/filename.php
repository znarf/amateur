<?php

return function($type, $name) {
  $base_dir = app_dir();
  $base_paths = [$base_dir, $base_dir . "/{$type}s"];
  foreach ($base_paths as $base_path) {
    $filename = $base_path . "/{$name}.{$type}.php";
    if (file_exists($filename)) {
      return $filename;
    }
  }
};
