<?php

class sql extends SQLite3 {
  private $db;

  public function __construct() {
    $this->db = $this->open(DB_NAME);
  }
}

?>
