<?php namespace amateur\model;

class resource
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
