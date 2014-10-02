<?php namespace amateur\magic;

trait callable_properties
{

  protected $methods = [];

  public function __call($name, $args)
  {
    if (isset($this->methods[$name])) {
      $callable = $this->methods[$name];
      if (!$args) {
        return $callable();
      }
      switch (count($args)) {
        case 1: return $callable($args[0]);
        case 2: return $callable($args[0], $args[1]);
        case 3: return $callable($args[0], $args[1], $args[2]);
      }
      return call_user_func_array($callable, $args);
    }
    throw new \BadMethodCallException('Unknown method/property.');
  }

  public function __get($name)
  {
    if (isset($this->methods[$name])) {
      return $this->methods[$name];
    }
  }

  public function __set($name, $value)
  {
    if ($value instanceof \closure) {
      $this->methods[$name] = $value->bindTo($this, get_class());
    }
    else {
      $this->$name = $value;
    }
  }

}
