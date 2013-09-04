<?php namespace Amateur\Model;

class Table
{

  public $primary;

  public $classname;

  public $tablename;

  public $unique_indexes = [];

  function primary()
  {
    if (!$this->primary) {
      throw Exception("No primary set for '{$this->tablename}'");
    }
    return $this->primary;
  }

  function cache_key($key, $value, $type = 'raw')
  {
     return "{$this->tablename}_{$type}_{$key}_{$value}";
  }

  function preload($key, $ids)
  {
    $cache_keys = array_map(function($id) use($key) { return $this->cache_key($key, $id); }, $ids);
    Cache::preload($cache_keys);
  }

  function get_one($key, $value)
  {
    # From Cache
    $use_cache = in_array($key, $this->unique_indexes);
    if ($use_cache) {
      $cache_key = $this->cache_key($key, $value);
      $row = Cache::get($cache_key);
      if (is_array($row)) {
        return new $this->classname($row);
      }
      elseif ($row === 0) {
        return null;
      }
    }
    # From DB
    $row = $this->fetch_one([$key => $value]);
    if ($use_cache) {
      Cache::set($cache_key, $row ? $row : 0);
    }
    if ($row) {
      return new $this->classname($row);
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
        $row = Cache::get($this->cache_key($key, $value), true);
        if (is_array($row)) {
          $objects[$value] = new $this->classname($row);
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
            Cache::set($this->cache_key($key, $value), $row ? $row : 0);
          }
          $objects[$value] = new $this->classname($row);
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

  function insert($set)
  {
    return $this->query()->insert()->set($set)->execute();
  }

  function create($set)
  {
    $this->insert($set);
    return $this->get(core('db')->insert_id());
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
        Cache::set($cache_key, $row);
      }
      return new $this->classname($row);
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
        Cache::delete($cache_key);
      }
    }
  }

  function to_objects($rows)
  {
    return array_map(function($row) { return new $this->classname($row); }, $rows);
  }

  # Query

  function query()
  {
    return new Query($this->tablename);
  }

  function select($columns = null)
  {
    return $this->query()->select($columns);
  }

  function fetch_one($where = [])
  {
    return $this->select()->where($where)->fetch_one();
  }

  function fetch_all($where = [])
  {
    return $this->select()->where($where)->fetch_all();
  }

  function fetch_object($where = [])
  {
    return $this->select()->where($where)->fetch_object($this->classname);
  }

  function fetch_objects($where = [])
  {
    return $this->select()->where($where)->fetch_objects($this->classname);
  }

}
