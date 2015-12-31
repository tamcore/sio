<?php

class log {
  private $sql;

  public function __construct() {
    $this->sql = new sql;
  }

  public function logCall($direction, $source, $destination, $action = "") {
    $stmt = $this->sql->prepare("INSERT INTO callog (source, destination, direction, action) VALUES (:source, :destination, :direction, :action)");
    $stmt->bindValue(':direction', $direction, SQLITE3_TEXT);
    $stmt->bindValue(':source', $source, SQLITE3_INTEGER);
    $stmt->bindValue(':destination', $destination, SQLITE3_INTEGER);
    $stmt->bindValue(':action', $action, SQLITE3_TEXT);
    $stmt->execute();
  }

}

?>