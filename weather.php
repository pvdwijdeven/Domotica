<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$key_val = $row['weather_API'];
	$key2_val = $row['weather2_API'];
	$status="current";
	if (!empty($_GET['status'])){
		$status=$_GET['status'];
	}
	if ($status=="current"){
		$source_url = 'http://api.openweathermap.org/data/2.5/weather?q=Kraaijenstein,nl&APPID='.$key_val.'&lang=nl&units=metric';
	}elseif ($status=="hourly"){
		$source_url = 'http://api.wunderground.com/api/'.$key2_val.'/hourly/lang:NL/q/52.0387895,4.2280437.json';
	}else{
		$source_url = 'http://api.wunderground.com/api/'.$key2_val.'/forecast10day/lang:NL/q/52.0387895,4.2280437.json';
	}
	$response=file_get_contents($source_url);
	echo $response;
?>
