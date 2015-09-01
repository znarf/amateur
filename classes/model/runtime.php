<?php namespace amateur\model;

class runtime
{

  public static $objects = [];

  public static function flush()
  {
    self::$objects = [];
  }

}
