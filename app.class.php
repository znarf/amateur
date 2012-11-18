<?php

namespace Core;

class App
{

  static $dir = null;

  static function dir($value = null)
  {
    return isset($value) ? self::$dir = realpath($value) : self::$dir;
  }

  static function lib($names = array())
  {
    foreach ($names as $name) {
      include_once self::dir() . '/lib/' . $name . '.lib.php';
    }
  }

  /* Model */

  static function model($names = array())
  {
    foreach ($names as $name) {
      include_once self::dir() . '/models/' . $name . '.model.php';
    }
  }

  /* Controller */

  static function module($name)
  {
    include self::dir() . '/modules/' . $name . '.module.php';
  }

  static function action($name, $params = array())
  {
    extract($params);
    include self::dir() . '/actions/' . $name . '.action.php';
  }

  static function helper($names = array())
  {
    foreach ($names as $name) {
      include_once self::dir() . '/helpers/' . $name . '.helper.php';
    }
  }

  /* Views */

  static function text($text = '')
  {
   echo htmlspecialchars($text);
  }

  static function view($name, $params = array())
  {
    extract($params);
    ob_start();
    include self::dir() . '/views/' . $name . '.view.php';
    return ob_get_clean();
  }

  static function layout($name, $content = '')
  {
    include self::dir() . '/layouts/' . $name . '.layout.php';
  }

  static function render($name, $params = array())
  {
    self::layout('default', self::view($name, $params));
  }

  /* Start */

  static function start($dir = null)
  {
    include self::dir($dir) . '/app.start.php';
  }

}
