<?php

namespace amateur
{

use amateur\core\amateur;

function url_start_with($str)
{
  return strpos(amateur::request_url(), $str) === 0;
}

}
