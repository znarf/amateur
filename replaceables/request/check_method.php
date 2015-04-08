<?php

namespace amateur
{

use amateur\core\amateur;

function check_method($methods)
{
  $methods = is_string($methods) ? explode(',', strtoupper($methods)) : $methods;
  if (!in_array(amateur::request_method(), $methods)) {
    throw amateur::http_error(405, 'Method Not Allowed');
  }
}

}
