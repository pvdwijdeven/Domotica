<?php
$file = fopen("log/" . $_GET['type'] . ".csv", "a");
// Open the file to get existing content
//$current = file_get_contents($file);
// Append a new person to the file
$current = $_GET['date'] . "," . $_GET['time'] . "," . $_GET['name'] . "\n";
//$current .= "test";
// Write the contents back to the file
fwrite($file, $current);
fclose($file);
?>
