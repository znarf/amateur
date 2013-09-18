<?php namespace amateur\model;

class ressource
{

  public $attributes;

  function __construct($attributes = [])
  {
    if (!empty($attributes)) {
      $this->set_attributes($attributes);
    }
  }

  function set_attributes($attributes = [])
  {
    $this->attributes = $attributes;
    foreach ($attributes as $key => $value) {
      $this->$key = $value;
    }
  }

  function attribute($name)
  {
    if (isset($this->attributes[$name])) return $this->attributes[$name];
  }

}

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
    # Should we cache or not, shouldn't the decision be done at the apprecation of the dynamic property?
    return $this->$name = $this->$name();
  }

  function __isset($name)
  {
    $this->$name = $this->$name();
    return isset($this->$name);
  }

}

trait other_tables
{

  static $namespace;

  function table($name)
  {
    if (!isset(static::$namespace)) {
      $class = get_class($this);
      static::$namespace = substr($class, 0, strrpos($class, '\\'));
    }
    return table::instance($name, static::$namespace);
  }

}
