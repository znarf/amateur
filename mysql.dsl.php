<?php

function db_params($params = null)
{
  static $db_params = null;
  return isset($params) ? $db_params = $params : $db_params;
}

function db_connection()
{
  static $connection = null;
  if (!isset($connection)) {
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

function db_insert($tablename, $set = array())
{
  $query = "INSERT INTO $tablename";
  $query .= ' SET ' . self::buildSet($set);
  $result = self::query($query);
  if (!$result) {
    throw new Exception(self::$con->error);
  }
  return true;
}

function db_update($tablename, $set = array(), $where = array())
{
  $query  = "UPDATE $tablename";
  $query .= ' SET ' . db_build_set($set);
  $query .= ' WHERE ' . (is_array($where) ? self::buildWhere($where) : $where);
  $result = db_query($query);
  if (!$result) {
    throw new Exception(self::$con->error);
  }
  return true;
}

function db_delete($tablename, $where = array())
{
  $query  = "DELETE FROM $tablename";
  $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  $result = db_query($query);
  if (!$result) {
    throw new Exception(self::$con->error);
  }
  return true;
}

function db_build_where($where = array())
{
  $_where = array();
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

function db_build_set($set = array())
{
  $_set = array();
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

function db_get_one($tablename, $where = array(), $fields = array())
{
  $fields = empty($fields) ? '*' : implode(', ', $fields);
  // some trouble with named fields in the development stage
  // so let's disable it for this deployment
  $fields = '*';
  $query  = "SELECT $fields FROM $tablename";
  $query .= ' WHERE ' . (is_array($where) ? db_build_where($where) : $where);
  $result = db_query($query);
  if ($result && $row = db_fetch_assoc($result)) {
    return $row;
  }
}

/*
function db_fetch_ids($result, $field = 'id', $type = 'int')
{
  $ids = array();

  if (is_object($result)) {
    // mysqli + mysql native driver
    if (method_exists($result, 'fetch_all')) {
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
        $ids[] = $type == 'int' ? (int)$row[$field] : $row[$field];
      }
    } else {
      $ids = array();
      while ($row = $result->fetch_assoc()) {
        $ids[] = $type == 'int' ? (int)$row[$field] : $row[$field];
      }
    }
  }

  return $ids;
}

function db_query_ids($query, $field = 'id', $type = 'int')
{
  $result = db_query($query);
  return db_fetch_ids($result, $field, $type);
}

function db_query_count($query)
{
  $result = db_query($query);
  return $result && $row = db_fetch_assoc($result) ? (int)$row['count'] : 0;
}
*/

function db_fetch_assoc($result)
{
  return $result->fetch_assoc();
}

function db_fetch_object($result, $classname = 'Ressource')
{
  if ($attributes = $result->fetch_assoc()) {
    return new $classname($attributes);
  }
  return false;
}

function db_fetch_objects($result, $classname = 'Ressource')
{
  $objects = array();
  while ($attributes = $result->fetch_assoc()) {
    $objects[] = new $classname($attributes);
  }
  return $objects;
}

function db_search($tablename, $params = array())
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
