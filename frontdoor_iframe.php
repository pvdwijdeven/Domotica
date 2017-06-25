<?php 
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
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="description" content="<?php echo $title; ?>">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=3.0">
		<link rel="stylesheet" href="css/frontdoor.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
	
	<script language="JavaScript" type="text/javascript">

		function reload()
		{
		   setTimeout('reloadImg("cam_frontdoor")',500)
		};

		function reloadImg(id) 
		{ 
		   var obj = document.getElementById(id); 
		   var date = new Date(); 
		   obj.src = "frontdoor.php?t=" + Math.floor(date.getTime()); 
		} 

	</script>


	<div id='cam_holder'><img src="frontdoor.php?t=" name="refresh" id="cam_frontdoor" onload='reload(this)' onerror='reload(this)'></div>

	</body>
</html>
<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		}
	} 
?>
