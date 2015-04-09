<?php namespace amateur;

function load_functions($dir)
{
  foreach (new \DirectoryIterator($dir) as $file) {
    if ($file->isFile() && $file->getExtension() == 'php') {
      require_once $file->getPathName();
    }
  }
}
