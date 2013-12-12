<?php

return function($type, $name, $instance = null) {
  return \amateur\core\registry::instance($type, $name, $instance);
};
