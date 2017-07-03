<?php 
	$dummy=$_SERVER;
	$title="Domo Dashboard";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	$sql = "SELECT * FROM config where row= 'config'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    $camkey=$row['CamKey'];

	if (array_key_exists ('HTTP_REFERER',$_SERVER)){
		$loginchecked=true;
	}else{
		$loginchecked=login_check($mysqli);
	};
	//make sure Default preset point is configured in camera!
	$command=$_GET['cmd'];
	if ($command=="ptzGotoPresetPoint"){
		$command.="&name=Default";
	}
	
	if (!$adminpage AND $loginchecked){
	$source_url = "http://192.168.1.67:88/cgi-bin/CGIProxy.fcgi?cmd=".$command."&". $camkey;
	echo $source_url;
	$response=file_get_contents($source_url);
	echo $response;
	if ($_GET['cmd']!="ptzGotoPresetPoint"){
		usleep(500000);
		$source_url = "http://192.168.1.67:88/cgi-bin/CGIProxy.fcgi?cmd=ptzStopRun&". $camkey;
		$response=file_get_contents($source_url);
		echo $response;
	}
	
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		}
	} 
?>
