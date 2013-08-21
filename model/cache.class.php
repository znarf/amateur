<?php namespace Amateur\Model;

class Cache
{

  static $memcache;

  static $cache = [];

  static $set = [];

  static $store_registered;

  static function preload($keys)
  {
    foreach ($keys as $id => $key) {
      if (isset(self::$cache[$key])) {
        unset($keys[$id]);
      }
    }

    if (empty($keys)) {
      return;
    }

    if (self::$memcache) {
      $values = self::$memcache->get($keys);
      if (is_array($values)) {
        foreach ($values as $key => $value) {
          self::$cache[$key] = $value;
        }
      }
    }
  }

  static function get($key)
  {
    if (array_key_exists($key, self::$cache)) {
      return self::$cache[$key];
    }

    if (self::$memcache) {
      error_log("cache_get:$key");
      return self::$cache[$key] = self::$memcache->get($key);
    }
  }

  static function set($key, $value, $expire = 0)
  {
    self::$set[$key] = [$value, $expire];
    self::$cache[$key] = $value;

    self::store_register();
    return;

    /*
    if (self::$memcache) {
      error_log("cache_set:$key");
      return self::$memcache->set($key, $value, false, $expire);
    }
    */
  }

  static function delete($key)
  {
    if (self::$memcache) {
      error_log("cache_delete:$key");
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
      error_log("cache_set:$key");
      self::$memcache->set($key, $value, false, $expire);
    }
    self::$set = [];
  }

}
