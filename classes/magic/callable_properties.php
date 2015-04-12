<?php namespace amateur\magic;

trait callable_properties
{

  protected $methods = [];

  public function __call($name, $args)
  {
    if (isset($this->methods[$name])) {
      return $this->methods[$name](...$args);
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
      $this->methods[$name] = $value->bindTo($this, __class__);
    }
    else {
      $this->$name = $value;
    }
  }

}
