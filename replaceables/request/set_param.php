<?php

namespace amateur
{

use amateur\core\amateur;

function set_param($name, $value)
{
  return amateur::request_param($name, $value);
}

}
