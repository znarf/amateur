<?php

namespace amateur
{

use amateur\core\replaceable;

function load_replaceables($dir, $namespace = null)
{
  return replaceable::load($dir, $namespace);
}

}
