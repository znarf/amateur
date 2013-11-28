<?php namespace amateur\model;

use pdo;

use http_exception;

class db
{

  static $params;

  static $connection;

  static function params($params = null)
  {
    return $params ? self::$params = $params : self::$params;
  }

  static function connection()
  {
    if (!self::$connection) {
      $options = [];
      $params = self::params();
      if (isset($params['charset'])) {
        $options[pdo::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES " . $params['charset'];
      }
      $dsn = 'mysql:dbname=' . $params['name'] . ';host=' . $params['host'];
      self::$connection = new pdo($dsn, $params['username'], $params['password'], $options);
    }
    return self::$connection;
  }

  static function execute($query)
  {
    # error_log($query);
    $connection = self::$connection ? self::$connection : self::connection();
    $result = $connection->query($query);
    if (!$result) {
      $error = $connection->errorInfo();
      throw new http_exception($error[2], 500);
    }
    return $result;
  }

  static function insert_id()
  {
    $connection = self::$connection ? self::$connection : self::connection();
    return $connection->lastInsertId();
  }

  static function quote($arg)
  {
    $connection = self::$connection ? self::$connection : self::connection();
    return $connection->quote($arg);
  }

  static function date($time, $format = 'Y-m-d H:i:s')
  {
    $timestamp = strtotime($time);
    return date($format, $timestamp);
  }

  static function now($format = 'Y-m-d H:i:s')
  {
    return date($format);
  }

  static function fetch_assoc($result)
  {
    return $result->fetch(pdo::FETCH_ASSOC);
  }

  static function fetch_all($result)
  {
    return $result->fetchAll();
  }

  static function fetch_ids($result, $key = 'id')
  {
    $rows = $result->fetchAll();
    $ids = [];
    foreach ($rows as $row) $ids[] = (int)$row[$key];
    return $ids;
  }

  static function fetch_key_values($result, $key, $value)
  {
    $rows = $result->fetchAll();
    $results = [];
    foreach ($rows as $row) $results[$row[$key]] = $row[$value];
    return $results;
  }

}
