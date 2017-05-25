<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$epoch=time();
$fields="ID, val".$_GET['measurement'];
$_GET['measurement'];
$val=$_GET['value'];
if ($val=="true") {$val=1;}
if ($val=="false") {$val=0;}


$sqlstring="INSERT INTO measurement_values (" . $fields . ") VALUES (" . $epoch . ", ". $val . ") ON DUPLICATE KEY UPDATE val".$_GET['measurement']."=val".$_GET['measurement']; 
file_put_contents("log/logfile.txt", "homeytosql.php ".$sqlstring."\n", FILE_APPEND | LOCK_EX);

$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);

?>