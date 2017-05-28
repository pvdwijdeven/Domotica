<?php
$file = fopen("log/" . $_GET['type'] . ".csv", "w");
$current = $_GET['date'] . "," . $_GET['time'] . "," . $_GET['name'] . "\n";
fwrite($file, $current);
fclose($file);
?>
