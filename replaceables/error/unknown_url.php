<?php

return function() {
  return error(404, sprintf("No url match '%s'.", request_url()));
};
