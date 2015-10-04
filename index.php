<?php

require 'config.php';

spl_autoload_register(function ($class) {
    require_once 'include/' . $class . '.class.php';
});

new main;

?>
