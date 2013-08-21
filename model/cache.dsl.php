<?php

use \Amateur\Model\Cache as Cache;

function cache_connection($connection = null)
{
  Cache::$memcache = $connection;
}

function cache_preload($keys)
{
  return Cache::preload($keys);
}

function cache_get($key)
{
  return Cache::get($key);
}

function cache_delete($key)
{
  return Cache::delete($key);
}

function cache_set($key, $value, $expire = 0)
{
  return Cache::set($key, $value, $expire);
}
