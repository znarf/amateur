<?php namespace amateur\magic;

class closure
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
    $args = empty($this->args) ? func_get_args() : $this->args;
    $object = $this->object;
    $method = $this->method;
    return $object->$method(...$args);
  }

  function __use()
  {
    $this->args = func_get_args();
    return $this;
  }

}
