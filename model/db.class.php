<?php namespace Amateur\Model;

use PDO;

use HttpException;

class Db
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
      self::$connection = new PDO($dsn, $params['username'], $params['password']);
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
      throw new HttpException($error[2], 500);
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
    return $result->fetch(PDO::FETCH_ASSOC);
  }

  static function fetch_all($result)
  {
    return $result->fetchAll();
  }

  static function fetch_object($result, $classname = '\Amateur\Model\Ressource')
  {
    return $result->fetchObject($classname);
  }

  static function fetch_objects($result, $classname = '\Amateur\Model\Ressource')
  {
    $objects = [];
    while ($object = $result->fetchObject($classname)) $objects[] = $object;
    return $objects;
  }

  static function fetch_ids($result, $key = 'id')
  {
    $ids = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) $ids[] = (int)$row[$key];
    return $ids;
  }

}
