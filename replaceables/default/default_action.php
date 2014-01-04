<?php

return function($name) {
  if ($filename = filename('action', $name)) {
    return include $filename;
  }
};
