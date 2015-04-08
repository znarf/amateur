<?php

namespace amateur
{

use amateur\core\replaceable;

function load_replaceables($dir)
{
  return replaceable::instance()->load($dir);
}

}

