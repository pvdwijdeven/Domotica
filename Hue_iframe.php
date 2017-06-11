<?php 
	$title="Domo Hue_iframe";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$hue_url = $row['Hue'];
	
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
		<link rel="stylesheet" href="css/hue.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script src="js/jscolor.js"></script>
	</head>
	

	<body>
	
		<script>
		var Hue_url='<?php echo $hue_url; ?>';
		
		function HSV_RGB (h, s, u) {
			if (h === null) {
				return [ u, u, u ];
			}

			h /= 60;
			s /= 100;

			var i = Math.floor(h);
			var f = i%2 ? h-i : 1-(h-i);
			var m = u * (1 - s);
			var n = u * (1 - s * f);
			u=Math.round(u);
			n=Math.round(n);
			m=Math.round(m);
			switch (i) {
				case 6:
				case 0: return [u,n,m];
				case 1: return [n,u,m];
				case 2: return [m,u,n];
				case 3: return [m,n,u];
				case 4: return [n,m,u];
				case 5: return [u,m,n];
			}
		}
		
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
		
		function isLight(rgb) {
			return (
				0.213 * rgb[0] +
				0.715 * rgb[1] +
				0.072 * rgb[2] <
				255 / 2
			);
		};
		
			function getCurrent(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						console.log(currentresult);
						A=ctToRGB(currentresult[4].state.ct);
						$("#lamp4_bri").html(parseInt((currentresult[4].state.bri/254)*100)+"%");
						$('#lamp4_hue').css('color', 'rgb('+A.r+','+A.g+','+A.b+')');
						if (currentresult[4].state.on==true){
							$("#lamp4").html("AAN");
							if (isLight(A.r,A.b,A.g)){
								$('#lamp4').css('color','white');
							}else{
								$('#lamp4').css('color','black');
							}
							$('#lamp4').css('background-color', 'rgb('+A.r+','+A.g+','+A.b+')');
						}else{
							$("#lamp4").css('background-color',$("body").css('background-color'));
							$("#lamp4").css('color',$("body").css('color'));
							$("#lamp4").html("UIT");
						}

						var h=parseInt(currentresult[5].state.hue*360/65535);
						var s=parseInt(currentresult[5].state.sat/2.55);
						var v=120//parseInt(currentresult[5].state.bri);
						$("#lamp5_bri").html(parseInt((currentresult[5].state.bri/254)*100)+"%");
						$('#lamp5_hue').css('color','rgb('+ HSV_RGB(h,s,v).join(', ') +')');
						if (currentresult[5].state.on==true){
							$('#lamp5').css('background-color','rgb('+ HSV_RGB(h,s,v).join(', ') +')');
							$("#lamp5").html("AAN");
							if (isLight(HSV_RGB(h,s,v))){
								$('#lamp5').css('color','white');
							}else{
								$('#lamp5').css('color','black');
							}
							console.log('rgb('+ HSV_RGB(h,s,v).join(', ') +')');
						}else{
							$("#lamp5").css('background-color',$("body").css('background-color'));
							$("#lamp5").css('color',$("body").css('color'));
							$("#lamp5").html("UIT");
						}
						//$('#lamp5').css('background-color', 'rgb('+B.r+','+B.g+','+B.b+')');
					}		
				};
				xmlhttp.open("GET", 'Hue.php?Request=lights', true);
				xmlhttp.send();
				setTimeout(function(){ getCurrent(); }, 4000);
			}

			$( document ).ready(function()  {
				getCurrent();
			});

			function turnOnOff(element){
				if ($(element).html()=="AAN"){
					status=false;
				}else{
					status=true;
				}
				lamp=$(element).attr('id').substr(4);
				console.log('HuePut.php?on='+status+"&light="+lamp);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'HuePut.php?on='+status+"&light="+lamp, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						console.log(currentresult);
					}
				};
				xmlhttp.send();
			}

		</script>
		<div id="Hue_mainframe">
			<div class="lamp_frame"><div class="hue_lamp">Hanglamp</div><div class="hue_button" onclick='turnOnOff(this)' id='lamp4'></div><div id="lamp4_hue" class="hue_button">kleur</div><div id="lamp4_bri" class="hue_button">helderheid</div></div>
			<div class="lamp_frame"><div class="hue_lamp">kleurenlamp</div><div class="hue_button" onclick='turnOnOff(this)' id='lamp5'></div><div id="lamp5_hue" class="hue_button">kleur</div><div id="lamp5_bri" class="hue_button">helderheid</div></div>
			<div class="lamp_frame"><div class="hue_scene">sfeer dim</div><div class="hue_scene">sfeer 40%</div><div class="hue_scene">sfeer 60%</div><div class="hue_scene">sfeer 100%</div><div class="hue_scene">dag 100%</div><div class="hue_scene">UIT</div></div>
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
