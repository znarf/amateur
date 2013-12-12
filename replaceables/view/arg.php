<?php

return function($string) {
  return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
};
