<?php namespace amateur\model;

use redis as php_redis;

class redis
{

  static $params;

  static function params($params = null)
  {
    return $params ? self::$params = $params : self::$params;
  }

  static $connection;

  static function connection($connection = null)
  {
    if ($connection) {
      self::$connection = $connection;
    }
    if (self::$connection) {
      return self::$connection;
    }
    $params = self::params();
    $connection = new php_redis;
    $connection->pconnect($params['host']);
    return self::$connection = $connection;
  }

}
