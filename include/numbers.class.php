<?php

class numbers {
  private $sql, $number, $user;

  public function __construct($number = 0, $user = 0) {
    $this->sql    = new sql;
    $this->number = $number;
    $this->user   = $user;
  }

  public function setNumber($number) {
    $this->number = $number;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function getIftttKey() {
    $stmt = $this->sql->prepare("SELECT ifttt_key FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC)['ifttt_key'];
  }

  public function getDnd() {
    $stmt = $this->sql->prepare("SELECT dnd FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return (bool)$result->fetchArray(SQLITE3_ASSOC)['dnd'];
  }

  public function getDndAction() {
    $stmt = $this->sql->prepare("SELECT dnd_action FROM numbers WHERE number=:number;");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $action = $result->fetchArray(SQLITE3_ASSOC)['dnd_action'];
    $actions = new actions(0,0,0);
    if (empty($action) == false) {
      return $actions->parseAction($action);
    }
    return $actions->parseAction('hangup');
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
    if ($this->isValid() == true) return false;

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

  public function changeNumber($to) {
    if ($this->isValid() == false) return false;

    $stmt = $this->sql->prepare("UPDATE numbers SET number=:to WHERE number=:number");
    $stmt->bindValue(':to', $to, SQLITE3_INTEGER);
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return (bool)$this->sql->changes();
  }

  public function deleteNumber() {
    if ($this->isValid() == false) return false;

    $stmt = $this->sql->prepare("SELECT id FROM numbers WHERE number=:number AND user=:user");
    $stmt->bindValue(':number', $this->number, SQLITE3_INTEGER);
    $stmt->bindValue(':user', $this->user, SQLITE3_INTEGER);
    $result = $stmt->execute();
    if (!$id = $result->fetchArray(SQLITE3_ASSOC)['id'])
        return false;

    $stmt = $this->sql->prepare("DELETE FROM numbers WHERE id=:id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
    $changes = $this->sql->changes();

    $this->sql->exec("DELETE FROM actions WHERE number NOT In (SELECT DISTINCT id FROM numbers);");
    $changes = ($changes + $this->sql->changes());

    return (bool)$changes;
  }
}

?>
