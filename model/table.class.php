<?php namespace amateur\model;

use exception;

class table
{

  public $namespace;

  public $classname;

  public $tablename;

  public $primary = 'id';

  public $unique_indexes = ['id'];

  public $collection_indexes = [];

  public $default_limit = 1000;

  public $objects = [];

  function primary()
  {
    if (!$this->primary) {
      throw new exception("No primary set for '{$this->tablename}'");
    }
    return $this->primary;
  }

  function cache_key($key, $value, $type = 'raw')
  {
     return "{$this->tablename}_{$type}_{$key}_{$value}";
  }

  function preload($key, $ids)
  {
    $ids = array_filter($ids, function($id) { return is_int($id) || is_string($id); });
    $cache_keys = array_map(function($id) use($key) { return $this->cache_key($key, $id); }, $ids);
    cache::preload($cache_keys);
  }

  function get_one($key, $value)
  {
    # From local
    if (isset($this->objects[$key][$value])) {
      return $this->objects[$key][$value];
    }
    # From Cache
    $use_cache = in_array($key, $this->unique_indexes);
    if ($use_cache) {
      $cache_key = $this->cache_key($key, $value);
      $row = cache::get($cache_key);
      if (is_array($row)) {
        return $this->objects[$key][$value] = $this->to_object($row);
      }
      elseif ($row === 0) {
        return null;
      }
    }
    # From DB
    $row = $this->fetch_one([$key => $value]);
    if ($use_cache) {
      cache::set($cache_key, $row ? $row : 0);
    }
    if ($row) {
      return $this->objects[$key][$value] = $this->to_object($row);
    }
  }

  function get_all($key, $values)
  {
    $objects = [];
    # From Cache
    $use_cache = in_array($key, $this->unique_indexes);
    if ($use_cache) {
      foreach ($values as $i => $value) {
        $objects[$value] = null;
        # For now, we assume preloading
        $row = cache::get($this->cache_key($key, $value), true);
        if (is_array($row)) {
          $objects[$value] = $this->objects[$key][$value] = $this->to_object($row);
          unset($values[$i]);
        }
        elseif ($row === 0) {
          unset($values[$i]);
        }
      }
    }
    # From DB
    if (count($values)) {
      # We iterate over chunk of 1000
      foreach (array_chunk($values, 1000) as $values_chunk) {
        $rows = $this->fetch_all([$key => $values_chunk]);
        foreach ($rows as $row) {
          $value = $row[$key];
          if ($use_cache) {
            cache::set($this->cache_key($key, $value), $row ? $row : 0);
          }
          $objects[$value] = $this->objects[$key][$value] = $this->to_object($row);
        }
      }
    }
    return array_filter(array_values($objects));
  }

  function get($arg)
  {
    $key = $this->primary();
    if (is_array($arg)) {
      $this->preload($key, $arg);
      return $this->get_all($key, $arg);
    }
    else {
      return $this->get_one($key, $arg);
    }
  }

  function count($key, $value)
  {
    # From Cache
    $use_cache = in_array($key, $this->collection_indexes);
    if ($use_cache) {
      $cache_key = $this->cache_key($key, $value, 'count');
      $from_cache = cache::get($cache_key);
      if (is_integer($from_cache)) {
        return $from_cache;
      }
    }
    # From Db
    $where = [$key => $value];
    $count = $this->query()->count($where);
    # Update Cache
    if ($use_cache) {
      cache::set($cache_key, $count);
    }
    # Return
    return $count;
  }

  function ids($field, $key, $value, $offset = 0, $limit = -1)
  {
    # Query limit and offset can differ later from asked one
    $query_offset = $offset;
    $query_limit  = $limit;
    # From Cache
    $use_cache = in_array($key, $this->collection_indexes);
    if ($use_cache) {
      $cache_key = $this->cache_key($key, $value, 'ids');
      # If 0 results, nothing to query
      $count = $this->count($key, $value);
      if ($count == 0) {
        return [];
      }
      # If we have all results with default_limit
      # (given that there is less results than default_limit)
      # Or we have enough results with default_limit
      # (given that the max_offset (offset + limit) fits in default_limit)
      # Then we use 'soft offset/limit' with default values, and use cache
      if ($count <= $this->default_limit || $limit && ($offset + $limit) <= $this->default_limit) {
        $soft_offset_limit = true;
        $query_offset = 0;
        $query_limit = $this->default_limit;
        $ids = cache::get($cache_key);
      }
    }
    # From Db
    if (!isset($ids) || !is_array($ids)) {
      $where = [$key => $value];
      $ids = $this->where($where)->offset($query_offset)->limit($query_limit)->fetch_ids($field);
      # Only store default query
      if ($use_cache && $query_offset == 0 && $query_limit == $this->default_limit) {
        cache::set($cache_key, $ids);
      }
    }
    # Soft Offset/Limit
    if (isset($soft_offset_limit)) {
      $ids = array_slice($ids, $offset, $limit > 0 ? $limit : null);
    }
    # Return
    return $ids;
  }

  function create($set)
  {
    $this->insert()->set($set)->execute();
    # Get from Db
    $where = [$this->primary() => db::insert_id()];
    $row = $this->fetch_one($where);
    # Update cache
    foreach ($this->unique_indexes as $key) {
      $cache_key = $this->cache_key($key, $row[$key]);
      cache::set($cache_key, $row);
    }
    # Return object
    return $this->to_object($row);
  }

  function update($where, $set)
  {
    # Ressource given
    if (is_object($where)) {
      $ressource = $where;
      $key = $this->primary();
      $where = [$key => $ressource->$key];
    }
    # Update Db
    $this->query()->update()->where($where)->set($set)->execute();
    # Update Cache + Return ressource
    if (isset($ressource)) {
      $row = $this->fetch_one($where);
      foreach ($this->unique_indexes as $key) {
        $cache_key = $this->cache_key($key, $row[$key]);
        cache::set($cache_key, $row);
      }
      return $this->to_object($row);
    }
  }

  function delete($where)
  {
    # Ressource given
    if (is_object($where)) {
      $ressource = $where;
      $key = $this->primary();
      $where = [$key => $ressource->$key];
    }
    # Update Db
    $this->query()->delete()->where($where)->execute();
    # Delete Cache
    if (isset($ressource)) {
      foreach ($this->unique_indexes as $key) {
        $cache_key = $this->cache_key($key, $ressource->$key);
        cache::delete($cache_key);
      }
    }
  }

  function to_object($row)
  {
    if (!isset($this->namespace)) {
      $this->namespace = $this->current_namespace();
    }
    if ($this->classname) {
      $classname = $this->namespace ? $this->namespace . '\\' .  $this->classname : $this->classname;
    }
    else {
      $classname = '\amateur\model\ressource';
    }
    return new $classname($row);
  }

  function to_objects($rows)
  {
    return array_map([$this, 'to_object'], $rows);
  }

  # Query

  function query()
  {
    return new query($this->tablename);
  }

  function select($columns = null)
  {
    return $this->query()->select($columns);
  }

  function insert($columns = null)
  {
    return $this->query()->insert($columns);
  }

  function where($where)
  {
    return $this->query()->where($where);
  }

  function fetch_one($where = [])
  {
    return $this->where($where)->fetch_one();
  }

  function fetch_all($where = [])
  {
    return $this->where($where)->fetch_all();
  }

  function fetch_object($where = [])
  {
    $row = $this->where($where)->fetch_one();
    return $row ? $this->to_object($row) : null;
  }

  function fetch_objects($where = [])
  {
    $rows = $this->where($where)->fetch_all();
    return $this->to_objects($rows);
  }

  # Registry

  static $instances = [];

  static function instance($name, $ns = null, $direct = false)
  {
    $classname = $ns ? $ns . '\\' . $name : $name;
    if (isset(self::$instances[$classname])) {
      return self::$instances[$classname];
    }
    # Direct
    if ($direct) {
      return;
    }
    # Let autoload do its magic
    if (class_exists($classname)) {
      return self::$instances[$classname] = new $classname;
    }
    # Default
    else {
      $instance = new table;
      $instance->tablename = $name;
      return self::$instances[$classname] = $instance;
    }
  }

  static function flush()
  {
    foreach (self::$instances as $instance) $instance->objects = [];
  }

  function table($name)
  {
    if (!isset($this->namespace)) {
      $this->namespace = $this->current_namespace();
    }
    return table::instance($name, $this->namespace);
  }

  # Temporary

  function current_namespace()
  {
    $class = get_class($this);
    return substr($class, 0, strrpos($class, '\\'));
  }

  # Experimental

  function __invoke($name = null)
  {
    return $name ? $this->table($name) : $this;
  }

}
