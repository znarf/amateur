<?php

return function($text) {
  return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5);
};
