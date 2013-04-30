<?php

namespace Amateur\Model;

class Ressource
{

  protected $_attributes;

  function __construct($attributes = [])
  {
    $this->_attributes = $attributes;
    $methods = get_class_methods($this);
    $mkeys = array_flip($methods);
    foreach ($this->_attributes as $key => $value) {
      // if (!in_array($key, $methods)) {
      if (!isset($mkeys[$key])) {
        $this->$key = $value;
      }
    }
  }

  function __get($name)
  {
    return $this->$name = $this->$name();
  }

  function __isset($name)
  {
    $this->$name = $this->$name();
    return isset($this->$name);
  }

  function attribute($name)
  {
    if (isset($this->_attributes[$name])) return $this->_attributes[$name];
  }

}