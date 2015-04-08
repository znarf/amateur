<?php

namespace amateur
{

use amateur\core\loader;

function register_namespace($namespace_prefix, $base_dir)
{
  return loader::register_namespace($namespace_prefix, $base_dir);
}

}
