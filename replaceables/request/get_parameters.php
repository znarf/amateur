<?php namespace amateur;

function get_parameters($names)
{
  $parameters = [];
  foreach ($names as $name) {
    $parameters[$name] = amateur::request_param($name);
  }
  return $parameters;
}
