<?php

class api {
  private $callSource, $callDestination, $callDirection;

  public function __construct() {
    $this->setCallSource($_POST['from']);
    $this->setCallDestination($_POST['to']);
    $this->setCallDirection($_POST['direction']);
  }

  private function setCallSource($number) {
    $this->callSource = $number;
  }

  private function setCallDestination($number) {
    $this->callDestination = $number;
  }

  private function setCallDirection($direction) {
    $this->callDirection = $direction;
  }

  public function getCallDetails() {
    echo 'From:      ' . $this->callSource . PHP_EOL;
    echo 'To:        ' . $this->callDestination . PHP_EOL;
    echo 'Direction: ' . $this->callDirection . PHP_EOL;
  }
}

?>
