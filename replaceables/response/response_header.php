<?php

return function($name, $value) {
  header("$name:$value");
};
