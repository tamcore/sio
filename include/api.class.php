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

  public function dumpCallDetails() {
    echo 'From:      ' . $this->callSource . PHP_EOL;
    echo 'To:        ' . $this->callDestination . PHP_EOL;
    echo 'Direction: ' . $this->callDirection . PHP_EOL;
  }

  public function handleCall() {
    switch ($this->callDirection) {
      case 'in':
        $actions = new actions($this->callDirection, $this->callDestination, $this->callSource);
        break;
      case 'out':
        $actions = new actions($this->callDirection, $this->callSource, $this->callDestination);
        break;
      default;
        echo "Invalid direction:" . $this->callDirection . PHP_EOL;
        break;
    }
    $this->printResponse($actions->getAction());
  }

  private function printResponse($response) {
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<Response>' . PHP_EOL;
    echo $response . PHP_EOL;
    echo '</Response>' . PHP_EOL;
  }
}

?>
