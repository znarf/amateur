<?php

return function($type, $name) {
  $base_dir = app_dir();
  $filenames = [
    "{$base_dir}/{$name}.{$type}.php",
    "{$base_dir}/{$type}s/{$name}.{$type}.php",
    "{$base_dir}/{$type}s/{$name}.php"
  ];
  foreach ($filenames as $filename) {
    if (file_exists($filename)) {
      return $filename;
    }
  }
};
