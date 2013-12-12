<?php

return function($dir) {
  foreach (new \DirectoryIterator($dir) as $file) {
    if ($file->isFile() && $file->getExtension() == 'php') {
      require_once $file->getPathName();
    }
  }
};
