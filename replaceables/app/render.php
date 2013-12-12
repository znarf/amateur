<?php

return function($name, $args = []) {
  layout(view($name, $args));
  finish();
};
