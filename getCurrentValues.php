<?php
	$filename="log/current.csv";
	unlink($filename);
	
	include_once 'includes/db_connect.php';
	$api_url = 'http://192.168.1.67:8000/api/app/com.internet/getCurrent';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	
	
	$bearer = $row['bearer'];
	
	$context = stream_context_create(array(
	    'http' => array(
	        'header' => "Authorization:bearer " . $bearer,
	    ),
	));
		$result = file_get_contents($api_url, false, $context);
		if ($result===FALSE)
		{echo "Unauthorized request";}
		$maxtime=4;
		$curtime=0;
		while (!file_exists($filename) and $curtime<$maxtime){
			sleep(1);
			$curtime++;
		}
		if (file_exists($filename)){
			echo date ("Y/m/d H:i:s", filemtime($filename));
			$myfile = fopen($filename, "r") or die("Unable to open file!");
			echo strstr(ltrim(strstr(fread($myfile,filesize($filename)), ','),','),',');
			fclose($myfile);
		}
?>