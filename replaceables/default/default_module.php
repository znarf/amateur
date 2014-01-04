<?php

return function($name) {
  if ($filename = filename('module', $name)) {
    return include $filename;
  }
  throw http_error(500, "Unknown module ($name).");
};
