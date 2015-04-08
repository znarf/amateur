<?php

namespace amateur
{

use amateur\core\amateur;

function relative_url($path = '')
{
  return amateur::app_path() . $path;
}

}
