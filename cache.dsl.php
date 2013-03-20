<?php

function cache_connection($connection = null)
{
  static $cache_connection;
  return $connection ? $cache_connection = $connection : $cache_connection;
}

function cache_get($key)
{
  if ($connection = cache_connection()) return $connection->get($key);
}

function cache_delete($key)
{
  if ($connection = cache_connection()) return $connection->delete($key);
}

function cache_set($key, $var, $expire = 0)
{
  if ($connection = cache_connection()) return $connection->set($key, $var, false, $expire);
}
