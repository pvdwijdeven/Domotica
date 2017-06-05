<?php
	include_once 'includes/db_connect.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$key_val = $row['direction_API'];
	if (!empty($_GET['ID'])){
		$ID=$_GET['ID'];
		$OVresult = mysql_query('SELECT * FROM trafficINFO WHERE ID='.$ID);
	} else {
		$OVresult = mysql_query('SELECT * FROM trafficINFO WHERE DefaultYNID=1');
	}
	$OVINFO = mysql_fetch_row($OVresult, MYSQL_ASSOC);



	
	$source_url = 'https://maps.googleapis.com/maps/api/directions/json?';
	$destination = $OVINFO['Destination'];
	$origin = $OVINFO['Source'];
	$departure_time = 'now';
	$drive_mode = 'car';
	$alternatives = 'true';
	$source_url2 = $source_url . 'origin=' . $origin . '&destination=' . $destination . '&departure_time=' . $departure_time . '&mode=' . $drive_mode . '&alternatives=' . $alternatives . '&key=' . $key_val . '&language=nl';
	$response=file_get_contents($source_url2);
	echo $response;
?>
