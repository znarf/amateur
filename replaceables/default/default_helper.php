<?php

return function($name) {
  if ($filename = filename('helper', $name)) {
    return include $filename;
  }
  throw http_error(500, "Unknown helper ($name).");
};
