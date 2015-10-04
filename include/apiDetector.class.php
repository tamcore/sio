<?php

class apiDetector {
  public function isApiCall() {
    if (isset($_POST['from'], $_POST['to'], $_POST['direction']) == false)
      return false;
    if ($_POST['direction'] != 'in' AND $_POST['direction'] != 'out')
      return false;
    if (is_numeric($_POST['from']) == false OR is_numeric($_POST['to']) == false)
      return false;
    return true;
  }
}

?>
