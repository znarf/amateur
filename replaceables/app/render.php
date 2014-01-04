<?php

return function($name, $args = [], $layout = 'default') {
  layout($layout, view($name, $args));
  finish();
};
