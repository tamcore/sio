<?php

class main {
  function __construct() {
    if (apiDetector::isApiCall() == true) {
      header('Content-type: application/xml');
      $api = new api;
      $api->handleCall();
    } else {
      echo '// display web frontend';
    }
  }
}

?>
