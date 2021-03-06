<?php namespace amateur\model;

class table
{

  public $classname;

  public $tablename;

  public $primary = 'id';

  public $primaries = [];

  public $unique_indexes = ['id'];

  public $collection_indexes = [];

  public $default_limit = 1000;

  public $default_ttl = 0;

  function __construct($tablename = null)
  {
    if ($tablename) {
      $this->tablename = $tablename;
    }
  }

  function primary()
  {
    if (!$this->primary) {
      throw new exception("No primary set for '{$this->tablename}'");
    }
    return $this->primary;
  }

  function primaries()
  {
    return $this->primaries ?: [$this->primary];
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
    if (isset(runtime::$objects[$this->tablename][$key][$value])) {
      return runtime::$objects[$this->tablename][$key][$value];
    }
    # From Cache
    $use_cache = in_array($key, $this->unique_indexes);
    if ($use_cache) {
      $cache_key = $this->cache_key($key, $value);
      $row = cache::get($cache_key);
      if (is_array($row)) {
        return runtime::$objects[$this->tablename][$key][$value] = $this->to_object($row);
      }
    }
    # From DB
    $row = $this->fetch_one([$key => $value]);
    if ($row && $use_cache) {
      cache::set($cache_key, $row, $this->default_ttl);
    }
    if ($row) {
      return runtime::$objects[$this->tablename][$key][$value] = $this->to_object($row);
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
          $objects[$value] = runtime::$objects[$this->tablename][$key][$value] = $this->to_object($row);
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
          if ($row && $use_cache) {
            cache::set($this->cache_key($key, $value), $row, $this->default_ttl);
          }
          $objects[$value] = runtime::$objects[$this->tablename][$key][$value] = $this->to_object($row);
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
      if (is_numeric($from_cache)) {
        return $from_cache;
      }
    }
    # From Db
    $where = [$key => $value];
    $count = $this->query()->count($where);
    # Update Cache
    if ($use_cache) {
      cache::set($cache_key, $count, $this->default_ttl);
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
        cache::set($cache_key, $ids, $this->default_ttl);
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
    # Compute condition to read back from Db
    $insert_id = db::insert_id();
    if ($this->primary && $insert_id) {
      $where = [$this->primary => $insert_id];
    }
    else {
      $where = array_intersect_key($set, array_flip($this->primaries()));
    }
    # Get from Db
    $row = $this->fetch_one($where);
    # Update cache
    foreach ($this->unique_indexes as $key) {
      $cache_key = $this->cache_key($key, $row[$key]);
      cache::set($cache_key, $row, $this->default_ttl);
    }
    # Return object
    return $this->to_object($row);
  }

  function update($where, $set = [])
  {
    # Resource given
    if (is_object($where)) {
      $resource = $where;
      $where = array_intersect_key($resource->attributes, array_flip($this->primaries()));
    }
    # Sanity check
    if (!$where) {
      throw new exception("Where can't be empty.");
    }
    # Update Db
    $this->query()->update()->where($where)->set($set)->execute();
    # Get from Db
    $rows = $this->fetch_all($where);
    # Update Cache
    foreach ($rows as $row) {
      foreach ($this->unique_indexes as $key) {
        $value = $row[$key];
        $cache_key = $this->cache_key($key, $value);
        cache::set($cache_key, $row, $this->default_ttl);
        unset(runtime::$objects[$this->tablename][$key][$value]);
      }
    }
    # Return object
    if (count($rows) == 1) {
      return $this->to_object($row);
    }
  }

  function delete($where)
  {
    # Resource given
    if (is_object($where)) {
      $resource = $where;
      $where = array_intersect_key($resource->attributes, array_flip($this->primaries()));
    }
    # Get from Db
    $row = $this->fetch_one($where);
    # Delete From Db
    $this->query()->delete()->where($where)->execute();
    # Delete Cache
    foreach ($this->unique_indexes as $key) {
      $cache_key = $this->cache_key($key, $row[$key]);
      cache::delete($cache_key);
    }
  }

  function to_object($row)
  {
    $classname = $this->classname ?: '\amateur\model\resource';
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

  function fetch_ids($where = [], $key = null)
  {
    $key = $key ?: $this->primary();
    return $this->where($where)->fetch_ids($key);
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

}
