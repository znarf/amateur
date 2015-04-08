<?php

namespace amateur
{

use amateur\core\replaceable;

function replaceable($name, $replaceable = null)
{
  # Set
  if ($replaceable) {
    return replaceable::set($name, $replaceable);
  }
  # Get
  return replaceable::get($name);
}

}
