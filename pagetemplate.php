<?php 
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
		<title>Template page</title>
		<meta name="description" content="Template page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
	
		<script type="text/javascript">
		<!--

			window.onclick = function(event) {
				if (event.target == document.getElementById("mySidenav")) {
				console.log("OK");
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
			
		<div id="mySidenav" class="sidenav">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<table class="menutable">
				<tr><td><button class="menubutton" id="menuButton_1" onmouseup="window.location.href='domo_main.php'">domo main</button></td></tr>
				<tr><td><button class="menubutton" id="menuButton_2" onmouseup="window.location.href='whathappened.php'">What happened?</button></td></tr>
				<tr><td><button class="menubutton" id="menuButton_3" onmouseup="window.location.href='page3'">Go3</button></td></tr>
				<tr><td><button class="menubutton" id="menuButton_4" onmouseup="window.location.href='page4'">Go4</button></td></tr>
			</table>
		</div>
	
		<header>
			<div class="header_left"><button class="tablebutton" onclick="openNav()">Menu</button></div>
			
			<div class="header_right" id="loginfo"><?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			<br>logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b><br> (<a href="includes/logout.php">Log out</a>)</div>
			<div class="header_center">Homey Domotica</div>
			<hr>
		</header>


			

	
	
	
	
	
		<div>you are logged in as <?php echo htmlentities($_SESSION['username']); ?></div>





		<footer>
			<hr>
			<p>
			<?php include 'includes/footer.php'; ?>
			</p>
		</footer>
	</body>

<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		}
	} 
?>