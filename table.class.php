<?php

class Table
{

  static $primary;

  static $classname;

  static $tablename;

  static function get($id)
  {
    if (static::$primary) {
      return static::get_one([static::$primary => $id]);
    }
  }

  static function get_one($params = [])
  {
    if ($row = db_get_one(static::$tablename, $params)) {
      return new static::$classname($row);
    }
  }

  static function create($values = [])
  {
    $result = db_insert(static::$tablename, $values);
    if (static::$primary && $id = db_insert_id()) {
      $values[static::$primary] = $id;
    }
    return new static::$classname($values);
  }

}
