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
      $params = self::params();
      $dsn = 'mysql:dbname=' . $params['name'] . ';host=' . $params['host'];
      self::$connection = new pdo($dsn, $params['username'], $params['password']);
    }
    return self::$connection;
  }

  static function execute($query)
  {
    # error_log($query);
    $connection = self::connection();
    $result = $connection->query($query);
    if (!$result) {
      $error = $connection->errorInfo();
      throw new http_exception($error[2], 500);
    }
    return $result;
  }

  static function insert_id()
  {
    $connection = self::connection();
    return $connection->lastInsertId();
  }

  static function quote($arg)
  {
    $connection = self::connection();
    return $connection->quote($arg);
  }

  # Static Methods

  static function date($str, $format = 'Y-m-d H:i:s')
  {
    $timestamp  = strtotime($str);
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
    $ids = [];
    while ($row = $result->fetch(pdo::FETCH_ASSOC)) $ids[] = (int)$row[$key];
    return $ids;
  }

  static function fetch_key_values($result, $key, $value)
  {
    $results = [];
    while ($row = $result->fetch(pdo::FETCH_ASSOC)) $results[$row[$key]] = $row[$value];
    return $results;
  }

}
