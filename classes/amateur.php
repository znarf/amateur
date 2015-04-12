<?php namespace amateur;

class amateur
{

  use magic\single_instance;

  static $registry = [];

  static function __callStatic($name, $args)
  {
    if (isset(replaceable::$replaceables[$name])) {
      $callable = replaceable::$replaceables[$name];
    }
    else {
      $callable = replaceable::get($name, $throw_exception = true);
    }
    return $callable(...$args);
  }

  function __call($name, $args)
  {
    if (isset(replaceable::$replaceables[$name])) {
      $callable = replaceable::$replaceables[$name];
    }
    else {
      $callable = replaceable::get($name, $throw_exception = true);
    }
    return $callable(...$args);
  }

}
