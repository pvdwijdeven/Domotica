<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$hue_url = $row['Hue'];
	$Request=$_GET['Request'];
	$source_url = $hue_url . $Request;
	$response=file_get_contents($source_url);
	echo $response;
?>
