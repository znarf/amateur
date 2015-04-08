<?php

namespace amateur
{

use amateur\core\amateur;

function check_parameters($names)
{
  $parameters = [];
  $names = is_string($names) ? explode(',', $names) : $names;
  foreach ($names as $name) {
    if (null === $parameters[$name] = amateur::request_param($name)) {
      throw amateur::http_error(400, "Missing Parameter ($name)");
    }
  }
  return $parameters;
}

}
