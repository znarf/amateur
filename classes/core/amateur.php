<?php namespace amateur\core;

class amateur
{

  static function instance()
  {
    return registry::instance('core', 'amateur', __class__);
  }

  static function __callStatic($name, $args)
  {
    return replaceable::call($name, $args);
  }

  function __call($name, $args)
  {
    return replaceable::call($name, $args);
  }

  function __set($name, $value)
  {
    if ($value instanceof \closure) {
      return replaceable::set($name, $value);
    }
    else {
      $this->$name = $value;
    }
  }

  function __get($name)
  {
    if ($value = replaceable::get($name)) {
      return $value;
    }
  }

}
