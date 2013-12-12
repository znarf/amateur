<?php

return function($parameters) {
  $parameters = is_string($parameters) ? explode(',', $parameters) : $parameters;
  foreach ($parameters as $name) {
    if (request_param($name) === null) {
      throw http_error(400, "Missing Parameter ($name)");
    }
  }
};
