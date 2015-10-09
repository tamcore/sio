<?php

class actions {
  private $sql, $callDirection, $localNumber, $remoteNumber;

  public function __construct($callDirection, $localNumber, $remoteNumber) {
    $this->callDirection = $callDirection;
    $this->localNumber   = $localNumber;
    $this->remoteNumber  = $remoteNumber;
    $this->sql           = new sql;
  }

  public function getAction() {
    $stmt = $this->sql->prepare("SELECT a.extnumber,a.direction,a.action,a.param1,a.param2,a.type,n.number FROM actions AS a JOIN numbers AS n on n.id = a.number WHERE a.direction=:direction AND n.number=:localNumber AND (a.extnumber=:remoteNumber OR a.type=1) ORDER BY type;");
    $stmt->bindValue(':direction', $this->callDirection, SQLITE3_TEXT);
    $stmt->bindValue(':localNumber', $this->localNumber, SQLITE3_INTEGER);
    $stmt->bindValue(':remoteNumber', $this->remoteNumber, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $res  = '';
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $res = $this->parseAction($row);
      if ($res != '') break;
    }
    return $res;
  }

  private function parseAction($result) {
    if (is_array($result) == false) {
      $result = array('action' => $result, 'param1' => '', 'param2' =>'', 'type' => 0);
    }
    if ($result['type'] == 1) {
      if (@preg_match($result['extnumber'], $this->remoteNumber) == false)
        return false;
    }
    switch ($result['action']) {
      case 'dial':
        $result = $this->dialAction($this->callDirection, $result['param1'], $result['param2']);
        break;
      case 'play':
        $result = $this->playAction($result['param1'], $result['param2']);
        break;
      case 'reject':
        $result = $this->rejectAction($result['param1']);
        break;
      case 'hangup':
        $result = $this->hangupAction();
        break;
      default:
        echo 'Unknown action defined: ' . $result["action"] . PHP_EOL;
        break;
    }
    return $result;
  }

  private function rejectAction($reason) {
    if (empty($reason) == false) $reason = 'reason="' . $reason . '"';
    else $reason = '';
    return '<Reject ' . $reason . '/>';
  }

  private function playAction($url, $param) {
    $response = '<Play><Url>' . $url . '</Url></Play>';
    if (empty($param) == false) $response = $response . $this->parseAction($param);
    return $response;
  }

  private function dialAction($direction, $param1, $param2) {
    if ($direction == 'in') {
      $response = '<Dial>';
      if ($param1 == 'voicemail') $response .= '<Voicemail />';
      if (is_numeric($param1) == true) $response .= '<Number>' . $param1 . '</Number>';
    } elseif ($direction == 'out') {
      $response = '<Dial ';
      if ($param1 == 'anonymous') $response .= 'anonymous="true"';
      if (is_numeric($param1) == true) $response .= 'callerId="' . $param1 . '"';
      $response .= '><Number>' . $this->remoteNumber . '</Number>';
    }
    $response .= '</Dial>';
    return $response;
  }

  private function hangupAction() {
    return '<Hangup />';
  }
}

?>
