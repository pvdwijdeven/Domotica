<?php
	$dummy=$_SERVER;
	$title="Domo nieuw gezicht";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
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
		<link rel="stylesheet" href="css/newFace.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">
			<!--
			
				window.onclick = function(event) {
					if (event.target == document.getElementById("mySidenav")) {
					document.getElementById("mySidenav").style.width = "0";
					}
				}	
				
				function openNav() {
					document.getElementById("mySidenav").style.width = "100%";
				}
			
				function closeNav() {
					document.getElementById("mySidenav").style.width = "0";
				}
			
				//-->
		</script>
		<?php include "includes/header.php"; ?>

<form action="putFace.php" method="post" enctype="multipart/form-data">
    Selecteer afbeelding om te uploaden:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<form action="detectFace.php" method="post" enctype="multipart/form-data">
    Gebruik afbeelding van URL:
    <input type="text" name="faceURL" id="faceURL">
    <input type="submit" value="Upload Image" name="submit">
</form>
	<script language="JavaScript" type="text/javascript">
		
		function snapshot(){
			 window.location.href = "frontdoor.php?savefile=true";
		}
		
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

	<button onclick="snapshot()">snapshot</button>
		<footer>
			<hr>
			<p>
				<?php include 'includes/footer.php'; ?>
			</p>
		</footer>
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