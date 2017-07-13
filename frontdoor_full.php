<?php 
	$dummy=$_SERVER;
	$title="Domo Frontdoor view";
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
		<link rel="stylesheet" href="css/frontdoor.css?v=3.0">
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
		<!-- main page starts here -->	
			<script language="JavaScript" type="text/javascript">

		$( document ).ready(function()  {
				if(self!=top){
					$("header").hide();
					$("footer").hide();
				}
		});	
		
		function reload()
		{
		   setTimeout('reloadImg("cam_frontdoor_full")',500)
		};

		function reloadImg(id) 
		{ 
		   var obj = document.getElementById(id); 
		   var date = new Date(); 
		   obj.src = "frontdoor.php?t=" + Math.floor(date.getTime()); 
		} 

		function sendCommand(command){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'frontdoormove.php?cmd='+command, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						console.log(this.responseText);
					}
				};
				xmlhttp.send();
			}
		
	</script>



	<div id='cam_holder_full'><img src="frontdoor.php?t=" name="refresh" id="cam_frontdoor_full" onload='reload(this)' onerror='reload(this)'></div>
	<div  id="control_frontdoor_full"><table><tr><td></td><td><button onclick="sendCommand('ptzMoveUp')"></button></td><td></td></tr><tr><td><button onclick="sendCommand('ptzMoveLeft')"></button></td><td><button onclick="sendCommand('ptzGotoPresetPoint')"></button></td><td><button onclick="sendCommand('ptzMoveRight')"></button></td></tr>
	<tr><td></td><td><button onclick="sendCommand('ptzMoveDown')"></button></td><td></td></tr></table></div>
		<!-- main page ends here -->	
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
