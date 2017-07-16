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
		<link rel="stylesheet" href="css/DB_woonkamer.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<?php include "includes/header.php"; ?>
	
	<div id="dash_modal2"><iframe id="dash_frame"></iframe><button id="dash_terug" class="dash_menu" onclick="hideModal()">Terug</button></div>
	
	<div id="upperframe"><div id="leftframe"><iframe id="OVTframe" src="traffic_iframe2.php"></iframe><div id="OVTSchedule"><?php if($adminName==$_SESSION['username']) {echo('<button class="OVtbutton">Schedule</button>');} ?><button id='switch' class="OVtbutton" onclick='switchOVT()'>OV</button></div></div><div id="rightframe"><div id="upperrightframe"><iframe src="weather_iframe2.php" id="weerframe"></iframe><iframe id="statusframe" src="status_iframe2.php"></iframe></div><div id="lowerrightframe"><iframe id="camframe" src="frontdoor_iframe2.php"></iframe><iframe id="lightframe" src="Hue_iframe2.php"></iframe></div></div></div>
	<div id="buttonframe"><div id="dash_buttons"><button id="dash_fs" class="dash_menu" onclick='refresh()'>Fullscreen</button>
	<button id="dash_met" class="dash_menu" onclick="showModal(this)">Alle metingen</button>
		<button id="dash_plat" class="dash_menu" onclick="showModal(this)">Plattegrond</button>
		<button id="dash_agenda" class="dash_menu" onclick="showModal(this)">Agenda</button>
		<button id="dash_log" class="dash_menu" onclick="showModal(this)">Logboek</button>
		<button id="dash_log" class="dash_menu" onclick="showModal(this)">Buienradar</button></div>
	</div>
	<iframe id="meldingen" src="warning_iframe2.php"></iframe>
	
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
				
				$( document ).ready(function()  {
					$('header').hide();
					$('footer').hide();					
				});			
				
				function refresh(){
					if ($('#dash_fs').html()=="Fullscreen"){
						document.querySelector('body').webkitRequestFullscreen(); 
						$('#dash_fs').html("Herladen")
					}else{
						location.reload();
					}
				}
				
				var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
				var eventer = window[eventMethod];
				var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
				var frontdoorcam = document.getElementById("camframe").contentWindow;

				// Listen to message from child window
				eventer(messageEvent,function(e) {
				  $("#dash_modal2").show();
				  $("#dash_modal2").attr("name",e.data);
				  $("#dash_frame").attr("src",e.data);
				  hide_timer = setTimeout(function(){hideModal(); }, 300000);
				  
				},false);
				
				function showModal(element) {
					$("#dash_modal2").show();
					$("#dash_modal2").attr("name",$(element).html());
					
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
					$("#dash_modal2").hide();
					$("#dash_modal2").attr("name","");
					$("#dash_frame").attr("src","");
					frontdoorcam.postMessage("go","*");
					clearTimeout(hide_timer);
				}
				
				function switchOVT() {
					if ($("#OVTframe").attr("src")=='traffic_iframe2.php'){
						$("#OVTframe").attr("src","OV_iframe2.php");
						$("#switch").html("Verkeer");
					}else{
						$("#OVTframe").attr("src","traffic_iframe2.php");
						$("#switch").html("OV");
					}
				}		
				//-->
		</script>

	
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