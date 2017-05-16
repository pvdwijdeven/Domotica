<?php
include_once 'psl-config.php';   // As functions.php is not included
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
mysql_connect(HOST,USER,PASSWORD);
	mysql_select_db("measurements");
$mysqlmeasi = new mysqli(HOST, USER, PASSWORD, DATABASE_M);
?>
