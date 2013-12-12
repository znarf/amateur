<?php namespace amateur\magic;

trait closurable_methods
{

  # Allow class/object methods to be passed as anonymous functions
  public function __get($name)
  {
    if (!is_callable([$this, $name])) throw new \Exception("Unknown method/property ($name).");
    return new closure($this, $name);
  }

}
