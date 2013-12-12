<?php

return function() {
  return (string)request_header('Referer');
};
