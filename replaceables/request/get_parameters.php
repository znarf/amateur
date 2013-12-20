<?php

return function($names) {
  $parameters = [];
  foreach ($names as $name) {
    $parameters[$name] = request_param($name);
  }
  return $parameters;
};
