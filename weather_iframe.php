<?php 
	$title="Domo Weather_iframe";
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
	
		<script>
			function getCurrent(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						console.log(currentresult);
						processCurrent(currentresult);
					}
				};
				xmlhttp.open("GET", "weather.php?status=current", true);
				xmlhttp.send();
			}

			function getForecast(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var forecastresult = JSON.parse(this.responseText);
						console.log(forecastresult);
						processForecast(forecastresult);
					}
				};
				xmlhttp.open("GET", "weather.php?status=forecast", true);
				xmlhttp.send();
			}

			$( document ).ready(function()  {
				getCurrent();
				getForecast();
		//		if (window.self == window.top){
		//			setTimeout(function(){ window.location.href = "domo_dashboard.php"; }, 60000);
		//		}
			});

			function processCurrent(current){
				$("#weather_icon").html('<img src="resources/weather/'+current.weather[0].icon+'.png" width="150px" height="90px">');
				$("#weather_temp").html("<div class='emphasize'>"+Math.round(current.main.temp)+"&degC&nbsp</div>");
				$("#weather_current").html("luchtvochtigheid: "+current.main.humidity+"%<BR>"+current.weather[0].description);
				var sunr=current.sys.sunrise;
				var suns=current.sys.sunset;
				var d = new Date();
				var curepoch = Math.round(d.getTime() / 1000);
				if (curepoch<sunr || curepoch>suns){
					$('#weather_icon').css("background-color", "black");
					$('#weather_temp').css("background-color", "black");
					$('#weather_temp').css("color", "white");
				}else{
					$('#weather_icon').css("background-color", "0099cc");
					$('#weather_temp').css("background-color", "0099cc");
					$('#weather_temp').css("color", "black");				
				}
				

			}

			function processForecast(forecast){
				return true
			}

		</script>
		<div id="weather_main">
			<table id="weather"><tr><td id="weather_icon"></td><td id="weather_temp"></td></tr>
			<tr><td colspan='2' id="weather_current"></td></tr></table>

		</div>

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
