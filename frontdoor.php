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
	
	if (!$adminpage AND $loginchecked){
	$frontdoor = "http://192.168.1.67:88/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&";
	$source_url = $frontdoor . $camkey . "&" . time();
	$response=file_get_contents($source_url);
	if (!empty($_GET['savefile'])){
		$target_dir = "faces/";
		$files = glob($target_dir."*"); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}

		$target_file= $target_dir . strval(time()) . ".jpg";
		file_put_contents($target_file, $response);
		header('Location: detectFace.php?faceURL='.$target_file);
	}else{
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
