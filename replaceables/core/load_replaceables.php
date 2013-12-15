<?php

return function($dir) {
  return \amateur\core\replaceable::instance()->load($dir);
};
