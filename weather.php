<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$key_val = $row['weather_API'];
	$status="current";
	if (!empty($_GET['status'])){
		$status=$_GET['status'];
	}
	if ($status==current){
		$source_url = 'http://api.openweathermap.org/data/2.5/weather?q=Den%20Haag,nl&APPID='.$key_val.'&lang=nl&units=metric';
	}else{
		$source_url = 'http://api.openweathermap.org/data/2.5/forecast?q=Den%20Haag,nl&APPID='.$key_val.'&lang=nl&units=metric';
	}
	$response=file_get_contents($source_url);
	echo $response;
?>
