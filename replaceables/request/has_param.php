<?php

namespace amateur
{

use amateur\core\amateur;

function has_param($name)
{
  return amateur::request_param($name) !== null;
}

}
