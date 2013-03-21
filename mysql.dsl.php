<?php

function db_params($params = null)
{
  static $db_params;
  return $params ? $db_params = $params : $db_params;
}

function db_connection()
{
  static $connection;
  if (!$connection) {
      $db = db_params();
      $connection = new mysqli($db['host'], $db['username'], $db['password'], $db['name']);
      if ($connection->connect_error) {
        throw new Exception($connection->connect_errno . ' - ' . $connection->connect_error, 503);
      }
      if (isset($db['charset'])) {
        $connection->set_charset($db['charset']);
      }
  }
  return $connection;
}

function db_query($query)
{
  $connection = db_connection();
  $result = $connection->query($query);
  if (!$result) {
    throw new Exception($connection->error, 500);
  }
  return $result;
}

function db_quote($arg)
{
  if (is_array($arg)) {
    return array_map('db_quote', $arg);
  }

  if ($arg === TRUE) {
    return 'TRUE';
  } elseif ($arg === FALSE) {
    return 'FALSE';
  } elseif ($arg === NULL) {
    return 'NULL';
  } elseif ($arg === 'IS NULL') {
    return 'IS NULL';
  } elseif ($arg === 'IS NOT NULL') {
    return 'IS NOT NULL';
  } else {
    $connection = db_connection();
    return "'" . $connection->real_escape_string($arg) . "'";
  }
}

function db_now($format = 'Y-m-d H:i:s')
{
  return date($format);
}

function db_insert($tablename, $set = [])
{
  $query = "INSERT INTO $tablename";
  $query .= ' SET ' . db_build_set($set);
  return db_query($query);
}

function db_update($tablename, $set = [], $where = [])
{
  $query  = "UPDATE $tablename";
  $query .= ' SET ' . db_build_set($set);
  $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  return db_query($query);
}

function db_delete($tablename, $where = [])
{
  $query  = "DELETE FROM $tablename";
  $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  return db_query($query);
}

function db_build_where($where = [])
{
  $_where = [];
  foreach ($where as $key => $value) {
    $value = db_quote($value);
    if (is_array($value)) {
      $_where[] = $key . ' IN (' . implode(',', $value) . ')';
    } elseif ($value == 'NULL' || $value == 'NOT NULL') {
      $_where[] = $key . ' IS ' . $value;
    } else {
      $_where[] = $key . ' = ' . $value;
    }
  }
  return implode(' AND ', $_where);
}

function db_build_set($set = [])
{
  $_set = [];
  foreach ($set as $key => $value) {
    $_set[] = $key . ' = ' . db_quote($value);
  }
  return implode(', ', $_set);
}

function db_insert_id()
{
  $connection = db_connection();
  return $connection->insert_id;
}

function db_get_one($tablename, $where = [], $fields = [])
{
  $fields = empty($fields) ? '*' : implode(', ', $fields);
  $query  = "SELECT $fields FROM $tablename";
  $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  $result = db_query($query);
  if ($result && $row = $result->fetch_assoc()) {
    return $row;
  }
}

function db_fetch_objects($result, $classname = '\Core\Ressource')
{
  $objects = [];
  while ($attributes = $result->fetch_assoc()) $objects[] = new $classname($attributes);
  return $objects;
}

function db_fetch_ids($result, $key = 'id')
{
  $ids = [];
  while ($row = $result->fetch_assoc()) $ids[] = (int)$row[$key];
  return $ids;
}

function db_fetch_all($result)
{
  $rows = [];
  while ($row = $result->fetch_assoc()) $rows[] = $row;
  return $rows;
}

function db_fetch_assoc($result)
{
  return $result->fetch_assoc();
}

function db_search($tablename, $params = [])
{
  extract($params);

  $query = 'SELECT ' . (empty($fields) ? '*' : implode(', ', $fields)) . ' FROM ' . $tablename;

  if (isset($where)) {
    $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  }

  if (isset($order)) {
    $query .= " ORDER BY $order";
  }

  if (isset($limit) && $limit > 0) {
    $query .= " LIMIT " . (int)$limit;
    if (isset($offset) && $offset > 0) {
      $query .= " OFFSET " . (int)$offset;
    }
  }

  return db_query($query);
}
