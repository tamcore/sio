<?php

class api {
  private $callSource, $callDestination, $callDirection, $callDiversion;

  public function __construct() {
    $this->setCallSource($_POST['from']);
    $this->setCallDestination($_POST['to']);
    $this->setCallDirection($_POST['direction']);
    if (isset($_POST['diversion'])) $this->setCallDiversion($_POST['diversion']);
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

  private function setCallDiversion($diversion) {
    $this->callDiversion = $diversion;
  }

  public function dumpCallDetails() {
    echo 'From:      ' . $this->callSource . PHP_EOL;
    echo 'To:        ' . $this->callDestination . PHP_EOL;
    echo 'Direction: ' . $this->callDirection . PHP_EOL;
    echo 'Diversion: ' . $this->callDiversion . PHP_EOL;
  }

  public function handleCall() {
    switch ($this->callDirection) {
      case 'in':
        if ($this->callDiversion) {
          $numbers = new numbers($this->callDiversion);
          if ($numbers->isValid() == true) {
            if ($numbers->getDnd() == true)
              $action = $numbers->getDndAction();
            else {
              $actions = new actions($this->callDirection, $this->callDiversion, $this->callSource);
              if ($actions->getAction())
                break;
            }
          }
        }
        $numbers = new numbers($this->callDestination);
        if ($numbers->isValid() == false) exit;
        if ($numbers->getDnd() == true)
          $action = $numbers->getDndAction();
        else
          $actions = new actions($this->callDirection, $this->callDestination, $this->callSource);
        break;
      case 'out':
        $numbers = new numbers($this->callSource);
        if ($numbers->isValid() == false) exit;
        $actions = new actions($this->callDirection, $this->callSource, $this->callDestination);
        break;
      default;
        echo "Invalid direction:" . $this->callDirection . PHP_EOL;
        break;
    }
    if (isset($action) == false) $action = $actions->getAction();

    $log = new log();
    $log->logCall($this->callDirection, $this->callSource, $this->callDestination, $action);

    $this->printResponse($action);
  }

  private function printResponse($response) {
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<Response>' . PHP_EOL;
    echo $response . PHP_EOL;
    echo '</Response>' . PHP_EOL;
    exit;
  }

  public function isApiCall() {
    if (isset($_POST['from'], $_POST['to'], $_POST['direction']) == false)
      return false;
    if (isset($_POST['user'][0]) AND $_POST['user'][0] == 'voicemail')
      return false;
    if ($_POST['direction'] != 'in' AND $_POST['direction'] != 'out')
      return false;
    if (is_numeric($_POST['from']) == false OR is_numeric($_POST['to']) == false)
      return false;
    return true;
  }
}

?>
