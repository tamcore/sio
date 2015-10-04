<?php

class main {
  function __construct() {
    if (apiDetector::isApiCall() == true) {
      $api = new api;
      $api->getCallDetails();
    } else {
      echo '// display web frontend';
    }
  }
}

?>
