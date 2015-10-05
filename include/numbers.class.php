<?php

class numbers {
  private $sql, $number, $user;

  public function __construct() {
    #$this->sql = new SQLite3(DB_NAME);
    $this->sql = new sql;
  }

  public function setNumber($number) {
    $this->number = $number;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function getDnd() {
    $stmt = $this->sql->prepare("SELECT dnd FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC)['dnd'];
  }

  public function setDnd($dnd = 0) {
    // set dnd value in db
    $stmt = $this->sql->prepare("UPDATE numbers SET dnd=:dnd WHERE number=:number");
    $stmt->bindValue(':dnd', $dnd, SQLITE3_INTEGER);
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();

    // verify operation
    if ($dnd != (int)$this->getDnd()) return false;
    return true;
  }

  public function isValid() {
    $stmt = $this->sql->prepare("SELECT id FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    if (isset($result->fetchArray(SQLITE3_ASSOC)['id']))
      return true;
    return false;
  }

  public function addNumber() {
    // add number
    $stmt = $this->sql->prepare("INSERT INTO numbers (number, user, dnd) VALUES (:number, :user, 0)");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $stmt->bindValue(':user', $this->user, SQLITE3_INTEGER);
    $stmt->execute();

    // verify operation
    $stmt = $this->sql->prepare("SELECT id FROM numbers WHERE number=:number and user=:user");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $stmt->bindValue(':user', $this->user, SQLITE3_INTEGER);
    $result = $stmt->execute();
    if (isset($result->fetchArray(SQLITE3_ASSOC)['id']))
      return true;

    return false;
  }
}

?>
