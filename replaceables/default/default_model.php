<?php

namespace amateur
{

use amateur\core\amateur;
use amateur\core\exception;

function default_model($name)
{
  if ($filename = amateur::filename('model', $name)) {
    return include $filename;
  }
  throw new exception("Unknown model ($name).", 500);
}

}
