<?php namespace Amateur\Model;

class Cache
{

  static $memcache;

  static $cache = [];

  static $set = [];

  static $store_registered;

  static function connection($connection)
  {
    self::$memcache = $connection;
  }

  static function preload($keys)
  {
    $keys = array_unique($keys);
    $keys = array_filter($keys, function($key) { return !Cache::loaded($key); });

    if (empty($keys)) {
      return;
    }

    if (self::$memcache) {
      $keys = array_values($keys);
      # error_log("cache_preload:" . $keys[0] . " & " . (count($keys) - 1)  . " others");
      foreach (array_chunk($keys, 10000) as $keys_chunk) {
        $values = self::$memcache->get($keys_chunk);
        if (is_array($values)) {
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

    if (self::$memcache) {
      # error_log("cache_get:$key");
      return self::$cache[$key] = self::$memcache->get($key);
    }
  }

  static function set($key, $value, $expire = 0)
  {
    self::$set[$key] = [$value, $expire];
    self::$cache[$key] = $value;
    self::store_register();
  }

  static function delete($key)
  {
    if (self::$memcache) {
      # error_log("cache_delete:$key");
      return self::$memcache->delete($key, 0);
    }
  }

  public static function store_register()
  {
    if (!self::$store_registered) {
      register_shutdown_function(['\Amateur\Model\Cache', 'store']);
      self::$store_registered = true;
    }
  }

  public static function store()
  {
    foreach (self::$set as $key => $_set) {
      list($value, $expire) = $_set;
      # error_log("cache_set:$key");
      self::$memcache->set($key, $value, MEMCACHE_COMPRESSED, $expire);
    }
    self::$set = [];
  }

}
