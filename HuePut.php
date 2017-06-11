<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$hue_url = $row['Hue'];
	$light=$_GET['light'];
	$on=$_GET['on'];
	$on=($on=="true");
 	$arrData['on'] = $on;
	$data = json_encode($arrData);
	$url = $hue_url."lights/".$light."/state";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	$response = curl_exec($ch);
	echo $response; 
?>