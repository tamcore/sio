<?php

class numbers {
  private $sql;

  public function __construct() {
    $this->sql = new sql;
  }

  public function getDnd($number) {
    $stmt = $this->sql->prepare("SELECT dnd FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC)['dnd'];
  }

  public function isValid($number) {
    $stmt = $this->sql->prepare("SELECT id FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    if (isset($result->fetchArray(SQLITE3_ASSOC)['id']))
      return true;
    return false;
  }
}

?>
