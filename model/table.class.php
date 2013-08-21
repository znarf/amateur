<?php namespace Amateur\Model;

class Table
{

  public $primary;

  public $classname;

  public $tablename;

  public $unique_indexes = [];

  function get($id)
  {
    if ($this->primary) {
      return $this->get_one($this->primary, $id);
    }
  }

  function with_ids($ids)
  {
    $ressources = [];
    foreach ($ids as $id) if ($ressource = $this->get($id)) $ressources[] = $ressource;
    return $ressources;
  }

  function get_one($key, $value)
  {
    # From Cache
    $use_cache = in_array($key, $this->unique_indexes);
    $cache_key = $this->tablename . "_raw_" . $key . "_" . $value;
    if ($use_cache) {
      $row = cache_get($cache_key);
      if (is_array($row)) {
        return new $this->classname($row);
      }
      elseif ($row === 0) {
        return null;
      }
    }
    # From DB
    $row = db_get_one($this->tablename, [$key => $value]);
    if ($use_cache) {
      cache_set($cache_key, $row ? $row : 0);
    }
    if ($row) {
      return new $this->classname($row);
    }
  }

  function find_one($where = [])
  {
    $row = db_get_one($this->tablename, $where);
    if ($row) {
      return new $this->classname($row);
    }
  }

  function search($params = [])
  {
    $result = db_search($this->tablename, $params);
    return $result;
  }

  function create($values = [])
  {
    $result = db_insert($this->tablename, $values);
    if ($this->primary && $id = db_insert_id()) {
      # Update Cache
      $row = db_get_one($this->tablename, [$this->primary => $id]);
      foreach ($this->unique_indexes as $key) {
        $cache_key = $this->tablename . "_raw_" . $key . "_" . $row[$key];
        cache_set($cache_key, $row);
      }
      return new $this->classname($row);
    }
    return new $this->classname($values);
  }

  function update($ressource, $set = [])
  {
    if ($primary = $this->primary) {
      $where = [$primary => $ressource->$primary];
      # Update Db
      $result = db_update($this->tablename, $set, $where);
      # Update Cache
      $row = db_get_one($this->tablename, $where);
      foreach ($this->unique_indexes as $key) {
        $cache_key = $this->tablename . "_raw_" . $key . "_" . $row[$key];
        cache_set($cache_key, $row);
      }
      # Return new ressource
      return new $this->classname($row);
    }
  }

  function delete($ressource)
  {
    if ($primary = $this->primary) {
      $where = [$primary => $ressource->$primary];
      db_delete($this->tablename, $where);
      foreach ($this->unique_indexes as $key) {
        cache_delete($this->tablename . "_raw_" . $key . "_" . $ressource->$key);
      }
    }
  }

  function delete_where($where = [])
  {
    return db_delete($this->tablename, $where);
  }

}
