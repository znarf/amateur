<?php

# Useful to create closure with Non-Closurizable elements
function closure($callable, $a0 = null, $a1 = null, $a2 = null, $a3 = null)
{
  return function() use($callable, $a0, $a1, $a2, $a3) { return $callable($a0, $a1, $a2, $a3); };
}

trait Closurize
{

  # Create anonymous functions from class methods using Klosure class
  public function __klosure()
  {
    $args = func_get_args();
    $method = array_shift($args);
    if (!is_callable([$this, $method])) throw new Exception("Unknown method ($method).");
    $klosure = new \Klosure($this, $method);
    $klosure->args = $args;
    return $klosure;
  }

  # Create anonymous functions from class methods using native Closure
  public function __closure()
  {
    $object = $this;
    $args = func_get_args();
    $method = array_shift($args);
    if (!is_callable([$object, $method])) throw new Exception("Unknown method ($method).");
    foreach ($args as $n => $value) ${"a{$n}"} = $value;
    switch (count($args)) {
      case 0:  return [$object, $method];
      case 1:  return function() use($object, $method, $a0) { return $object->$method($a0); };
      case 2:  return function() use($object, $method, $a0, $a1) { return $object->$method($a0, $a1); };
      case 3:  return function() use($object, $method, $a0, $a1, $a2) { return $object->$method($a0, $a1, $a2); };
      case 4:  return function() use($object, $method, $a0, $a1, $a2, $a3) { return $object->$method($a0, $a1, $a2, $a3); };
      default: throw new Exception('Only a maximum of 4 args supported for __closure.');
    }
  }

  /*
  public function __closure($method, $a0 = null, $a1 = null, $a2 = null, $a3 = null)
  {
    $object = $this;
    if (!is_callable([$object, $method])) throw new Exception("Unknown method ($method).");
    return function() use($object, $method, $a0, $a1, $a2, $a3) { return $object->$method($a0, $a1, $a2, $a3); };
  }
  */

  # Allow class/object methods to be passed as anonymous functions
  public function __get($name)
  {
    if (!is_callable([$this, $name])) throw new Exception("Unknown method/property ($name).");
    return new \Klosure($this, $name);
  }

}

class Klosure
{

  public $object;

  public $method;

  public $args;

  function __construct()
  {
    $this->args = func_get_args();
    $this->object = array_shift($this->args);
    $this->method = array_shift($this->args);
  }

  function __invoke()
  {
    $object = $this->object;
    $method = $this->method;
    foreach ($this->args as $n => $value) ${"a{$n}"} = $value;
    switch (count($this->args)) {
      case 0:  return $object->$method();
      case 1:  return $object->$method($a0);;
      case 2:  return $object->$method($a0, $a1);
      case 3:  return $object->$method($a0, $a1, $a2);
      case 4:  return $object->$method($a0, $a1, $a2, $a3);
      default: throw new Exception('Only a maximum of 4 args supported for Klosure.');
    }
  }

  function __use()
  {
    $this->args = func_get_args();
    return $this;
  }

}
