<?php 
	$dummy=$_SERVER;
	$title="Domo Dashboard";
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">
			<!--
				var hide_timer;
				
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
				
				function showModal(element) {
					$("#dash_modal").show();
					$("#dash_modal").attr("name",$(element).html());
					
					if ($(element).html()=="Logboek"){
						$("#dash_frame").attr("src","whathappened.php");
					}
					if ($(element).html()=="Plattegrond"){
						$("#dash_frame").attr("src","floorplan.php");
					}
					if ($(element).html()=="Alle metingen"){
						$("#dash_frame").attr("src","domo_main.php");
					}
					if ($(element).html()=="Agenda"){
						$("#dash_frame").attr("src","calendar.php");
					}
					if ($(element).html()=="Buienradar"){
						$("#dash_frame").attr("src","buienradar.php");
					}					
					
					hide_timer = setTimeout(function(){hideModal(); }, 300000);
				}

				function hideModal() {
					$("#dash_modal").hide();
					$("#dash_modal").attr("name","");
					$("#dash_frame").attr("src","");
					clearTimeout(hide_timer);
				}
			
				//-->
		</script>
		<?php include "includes/header.php"; ?>
		<!-- main page starts here -->
		<div id="dash_modal"><iframe id="dash_frame"></iframe><button id="dash_terug" class="dash_menu" onclick="hideModal()">Terug</button></div>
		
		<div id="main_wrapper"><iframe id="frame_OV" src="OV_iframe.php"></iframe><iframe id="frame_weather" src="weather_iframe.php"></iframe><iframe id="frame_status" src="status_iframe.php"></iframe><iframe id="frame_traffic" src="traffic_iframe.php"></iframe><iframe id="frame_warning" src="warning_iframe.php"></iframe><iframe id="frame_hue" src="Hue_iframe.php"></iframe></iframe><iframe id="frame_camera" src="frontdoor_iframe.php"></iframe></div>
		<hr>
		<div id="dash_buttons"><button id="dash_met" class="dash_menu" onclick="showModal(this)">Alle metingen</button>
		<button id="dash_plat" class="dash_menu" onclick="showModal(this)">Plattegrond</button>
		<button id="dash_agenda" class="dash_menu" onclick="showModal(this)">Agenda</button>
		<button id="dash_log" class="dash_menu" onclick="showModal(this)">Logboek</button>
		<button id="dash_log" class="dash_menu" onclick="showModal(this)">Buienradar</button></div>
		
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
