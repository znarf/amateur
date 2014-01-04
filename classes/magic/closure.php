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
    if (!$args) {
      return $object->$method();
    }
    switch (count($args)) {
      case 1:  return $object->$method($args[0]);
      case 2:  return $object->$method($args[0], $args[1]);
      case 3:  return $object->$method($args[0], $args[1], $args[2]);
      default: return call_user_func_array([$object, $method], $args);
    }
  }

  function __use()
  {
    $this->args = func_get_args();
    return $this;
  }

}
