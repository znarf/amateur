<?php

namespace amateur
{

use amateur\core\amateur;

function static_url($path = '')
{
  return '//' . amateur::request_host() . amateur::app_path() . $path;
}

}
