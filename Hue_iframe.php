<?php 
	$title="Domo Hue_iframe";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
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
		<link rel="stylesheet" href="css/weather.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	</head>
	

	<body>
	
		<script>
		function ctToRGB(ct) {
			var kelvin=1000000/ct;
			var temp = kelvin / 100;
			var red, green, blue;
			if (temp <= 66) {
				red = 255;
				green = temp;
				green = 99.4708025861 * Math.log(green) - 161.1195681661;
				if (temp <= 19) {
					blue = 0;
				} else {
					blue = temp - 10;
					blue = 138.5177312231 * Math.log(blue) - 305.0447927307;
				}

			} else {
				red = temp - 60;
				red = 329.698727446 * Math.pow(red, -0.1332047592);
				green = temp - 60;
				green = 288.1221695283 * Math.pow(green, -0.0755148492);
				blue = 255;
			}
			return {
				r: Math.round(clamp(red, 0, 255)),
				g: Math.round(clamp(green, 0, 255)),
				b: Math.round(clamp(blue, 0, 255))
			}
		}


		function clamp(x, min, max) {
			if (x < min) {
				return min;
			}
			if (x > max) {
				return max;
			}
			return x;
		}
		
		
			function getCurrent(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						console.log(currentresult);
						A=ctToRGB(currentresult[4].state.ct);
						$('#test1').html(Math.round(currentresult[4].state.bri/2.54)+"%");
						$('#test1').css('background-color', 'rgb('+A.r+','+A.g+','+A.b+')');
						$('#test1').css('color', 'black');
					}
				};
				xmlhttp.open("GET", 'Hue.php?Request=lights', true);
				xmlhttp.send();
				setTimeout(function(){ getCurrent(); }, 4000);
			}
			
			
			$( document ).ready(function()  {
				getCurrent();
				
			});


		</script>
		<div id="Hue_mainframe">
			<div id='test1' style='width:100px; height:100px;'></div>
			<div id='test2' style='width:100px; height:100px;'></div>
		</div>
	</body>
</html>
<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
			echo substr($_SERVER['HTTP_REFERER'],-18);
			echo $loginchecked;
			echo $adminpage;
		}
	} 
?>
