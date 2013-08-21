<?php namespace Amateur\Model;

class Ressource
{

  protected $_attributes;

  function __construct($attributes = [])
  {
    if (!empty($attributes)) {
      $this->set_attributes($attributes);
    }
  }

  function set_attributes($attributes = [])
  {
    $this->_attributes = $attributes;
    foreach ($attributes as $key => $value) {
      $this->$key = $value;
    }
  }

  function attribute($name)
  {
    if (isset($this->_attributes[$name])) return $this->_attributes[$name];
  }

}

trait Dynamize
{

  protected static $methods;

  function set_attributes($attributes = [])
  {
    $this->_attributes = $attributes;
    # Store class methods
    if (!isset(static::$methods)) static::$methods = array_flip(get_class_methods($this));
    # Set properties that don't have a method named the same
    foreach ($attributes as $key => $value) {
      if (!isset(static::$methods[$key])) $this->$key = $value;
    }
  }

  function __get($name)
  {
    # Should we cache or not, shouldn't the decision be done at the apprecation of the dynamic property?
    return $this->$name = $this->$name();
  }

  function __isset($name)
  {
    $this->$name = $this->$name();
    return isset($this->$name);
  }

}
