<?php namespace amateur;

function is_write()
{
  return in_array(amateur::request_method(), ['POST', 'PATCH', 'PUT', 'DELETE']);
}
