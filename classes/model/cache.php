<?php namespace amateur\model;

class cache
{

  static $memcache;

  static $params = [];

  static $cache = [];

  static $set = [];

  static $store_registered;

  static function params($params = null)
  {
    return $params ? self::$params = $params + ['port' => 11211] : self::$params;
  }

  static function connection($connection = null)
  {
    if ($connection) {
      self::$memcache = $connection;
    }
    if (self::$memcache) {
      return self::$memcache;
    }
    if (!class_exists('\memcache') || !self::$params) {
      return;
    }
    $memcache = new \memcache;
    $memcache->addServer(self::$params['host'], self::$params['port']);
    return self::$memcache = $memcache;
  }

  static function preload($keys)
  {
    $keys = array_filter(array_unique($keys), function($key) { return !self::loaded($key); });

    if (empty($keys)) {
      return;
    }

    if ($memcache = self::connection()) {
      $keys = array_values($keys);
      # error_log("cache_preload:" . $keys[0] . " & " . count($keys)  . " total");
      foreach (array_chunk($keys, 10000) as $keys_chunk) {
        $get = $memcache instanceof \memcached ? 'getMulti' : 'get';
        $values = $memcache->$get($keys_chunk);
        if (is_array($values)) {
          # error_log("cache_preload:" . count($values)  . " found");
          foreach ($values as $key => $value) {
            self::$cache[$key] = $value;
          }
        }
      }
    }
  }

  static function loaded($key)
  {
    return isset(self::$cache[$key]) || array_key_exists($key, self::$cache);
  }

  static function get($key, $direct = false)
  {
    if (isset(self::$cache[$key]) || array_key_exists($key, self::$cache)) {
      return self::$cache[$key];
    }

    if ($direct) {
      return;
    }

    if ($memcache = self::connection()) {
      # error_log("cache_get:$key");
      return self::$cache[$key] = $memcache->get($key);
    }
  }

  static function set($key, $value, $expire = 0)
  {
    self::$set[$key] = [$value, $expire];
    self::$cache[$key] = $value;
    # Register storage
    if (!self::$store_registered) {
      register_shutdown_function(['\amateur\model\cache', 'store']);
      self::$store_registered = true;
    }
  }

  static function delete($key)
  {
    if ($memcache = self::connection()) {
      # error_log("cache_delete:$key");
      return $memcache->delete($key);
    }
  }

  public static function store()
  {
    if ($memcache = self::connection()) {
      foreach (self::$set as $key => $_set) {
        list($value, $expire) = $_set;
        # error_log("cache_set:$key");
        if ($memcache instanceof \memcached) {
          $memcache->set($key, $value, $expire);
        }
        else {
          $compressed = is_bool($value) || is_int($value) ? false : MEMCACHE_COMPRESSED;
          $memcache->set($key, $value, $compressed, $expire);
        }
      }
      self::$set = [];
    }
  }

  static function flush()
  {
    self::$cache = [];
  }

}
