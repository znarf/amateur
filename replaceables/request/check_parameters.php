<?php

return function($names) {
  $parameters = [];
  $names = is_string($names) ? explode(',', $names) : $names;
  foreach ($names as $name) {
    if (null === $parameters[$name] = request_param($name)) {
      throw http_error(400, "Missing Parameter ($name)");
    }
  }
  return $parameters;
};
