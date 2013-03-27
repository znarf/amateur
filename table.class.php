<?php

namespace Core;

class Table
{

  static $primary;

  static $classname;

  static $tablename;

  static $unique_indexes = [];

  static function get($id)
  {
    if (static::$primary) {
      return static::get_one(static::$primary, $id);
    }
  }

  static function get_one($key, $value)
  {
    // From Cache
    $use_cache = in_array($key, static::$unique_indexes);
    $cache_key = static::$tablename . "_one_" . $key . "_" . $value;
    if ($use_cache && $ressource = cache_get($cache_key)) {
      return $ressource;
    }
    // From DB
    if ($row = db_get_one(static::$tablename, [$key => $value])) {
      $ressource = new static::$classname($row);
      // Set Cache
      if ($use_cache) {
        cache_set($cache_key, $ressource);
      }
      return $ressource;
    }
  }

  static function create($values = [])
  {
    $result = db_insert(static::$tablename, $values);
    if (static::$primary && $id = db_insert_id()) {
      return static::get($id);
    }
    return new static::$classname($values);
  }

  static function delete($where = [])
  {
    return db_delete(static::$tablename, $where);
  }

}
