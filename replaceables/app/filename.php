<?php namespace amateur;

function filename($type, $name)
{
  $base_dir = amateur::app_dir();
  $filenames = [
    "{$base_dir}/{$type}s/{$name}.{$type}.php",
    "{$base_dir}/{$type}s/{$name}.php",
    "{$base_dir}/{$name}.{$type}.php",
  ];
  foreach ($filenames as $filename) {
    if (file_exists($filename)) {
      return $filename;
    }
  }
}
