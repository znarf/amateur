<?php namespace amateur\magic;

trait dynamic_properties
{

  protected static $methods;

  function set_attributes($attributes = [])
  {
    $this->attributes = $attributes;
    # Store class methods
    if (!isset(static::$methods)) static::$methods = array_flip(get_class_methods($this));
    # Set properties that don't have a method named the same
    foreach ($attributes as $key => $value) {
      if (!isset(static::$methods[$key])) $this->$key = $value;
    }
  }

  function __get($name)
  {
    if (method_exists($this, $name)) {
      # Should we cache or not?
      # Shouldn't the decision be done at the apprecation of the dynamic property?
      return $this->$name = $this->$name();
    }
    else {
      error_log("Undefined property ($name).");
    }
  }

  function __isset($name)
  {
    $this->$name = $this->$name();
    return isset($this->$name);
  }

}
