<?php

class main {
  function __construct() {
    if (apiDetector::isApiCall() == true) {
      echo '// parse api request';
    } else {
      echo '// display web frontend';
    }
  }
}

?>
