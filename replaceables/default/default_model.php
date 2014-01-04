<?php

return function($name) {
  if ($filename = filename('model', $name)) {
    return include $filename;
  }
  throw http_error(500, "Unknown model ($name).");
};
