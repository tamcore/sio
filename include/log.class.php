<?php

class log {
  private $sql;

  public function __construct() {
    $this->sql = new sql;
  }

  public function isLoggingEnabled($number) {
    $stmt = $this->sql->prepare("SELECT logging FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return (bool)$result->fetchArray(SQLITE3_ASSOC)['logging'];
  }

  public function logCall($direction, $source, $destination, $action = "") {
    if ($direction == 'in')
      $logging = $this->isLoggingEnabled($destination);
    elseif ($direction == 'out')
      $logging = $this->isLoggingEnabled($source);
    if ($logging) {
      $stmt = $this->sql->prepare("INSERT INTO callog (source, destination, direction, action) VALUES (:source, :destination, :direction, :action)");
      $stmt->bindValue(':direction', $direction, SQLITE3_TEXT);
      $stmt->bindValue(':source', $source, SQLITE3_INTEGER);
      $stmt->bindValue(':destination', $destination, SQLITE3_INTEGER);
      $stmt->bindValue(':action', $action, SQLITE3_TEXT);
      $stmt->execute();
    }
  }
}

?>