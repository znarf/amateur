<?php

namespace amateur
{

use amateur\core\amateur;

function url_is($str)
{
  return $str == amateur::request_url();
}

}
