<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$hue_url = $row['Hue'];
	$action=$_GET['action'];
	if ($action=='lights'){
		$light=$_GET['light'];
		if (array_key_exists('on',$_GET)){
			$on=$_GET['on'];
			$on=($on=="true");
			$arrData['on'] = $on;
		}
		if (array_key_exists('ct',$_GET)){
			$arrData['ct'] = intval($_GET['ct']);
		}
		if (array_key_exists('bri',$_GET)){
			$arrData['bri'] = intval($_GET['bri']);
		}
		if (array_key_exists('x',$_GET)){
			$x=floatval($_GET['x']);
			$y=floatval($_GET['y']);
			$arrData['xy'] = [$x,$y];
		}
		$data = json_encode($arrData);
		$url = $hue_url."lights/".$light."/state";
	}

	if ($action=='groups'){
		$group=$_GET['group'];
		$scene=$_GET['scene'];
		if ($scene=="UIT"){
			$arrData['on'] = false;
		}else{
			$arrData['scene'] = $scene;
		}
		$data = json_encode($arrData);
		$url = $hue_url."groups/".$group."/action";
	}	
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	$response = curl_exec($ch);
	echo $response; 
?>