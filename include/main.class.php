<?php

class main {
  function __construct() {
    if (api::isApiCall() == true) {
      header('Content-type: application/xml');
      $api = new api;
      $api->handleCall();
    } else {
      echo '// display web frontend';
    }
  }
}

?>
