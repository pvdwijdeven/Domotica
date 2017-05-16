<?php
include_once 'psl-config.php';   // As functions.php is not included
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
$mysqli_meas = new mysqli(HOST, USER, PASSWORD, DATABASE_MEAS);
?>
