<?php

namespace amateur
{

use amateur\core\registry;

function registry($type, $name, $instance = null)
{
  return registry::instance($type, $name, $instance);
}

}
