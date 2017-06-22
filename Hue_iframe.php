<?php 
	$dummy=$_SERVER;
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
		var scenes={};
		var groups={};
		var current={};
		
		hextorgb = function(hex){
			hex=parseInt("0x"+hex);
			var r = hex >> 16;
			var g = hex >> 8 & 0xFF;
			var b = hex & 0xFF;
			return [r,g,b];
		}
		
		function rgbtoxy(red,green,blue){
			red = (red > 0.04045) ? Math.pow((red + 0.055) / (1.0 + 0.055), 2.4) : (red / 12.92);
			green = (green > 0.04045) ? Math.pow((green + 0.055) / (1.0 + 0.055), 2.4) : (green / 12.92);
			blue = (blue > 0.04045) ? Math.pow((blue + 0.055) / (1.0 + 0.055), 2.4) : (blue / 12.92); 
			var X = red * 0.664511 + green * 0.154324 + blue * 0.162028;
			var Y = red * 0.283881 + green * 0.668433 + blue * 0.047685;
			var Z = red * 0.000088 + green * 0.072310 + blue * 0.986039;
			var x = X / (X + Y + Z);
			var y = Y / (X + Y + Z);
			return ([x,y]);
		}
		
		function xytorgb(x,y){
			var z = 1.0 - x - y;
			var Y = 1.0; // The given brightness value
			var X = (Y / y) * x;
			var Z = (Y / y) * z;

			var r =  X * 1.656492 - Y * 0.354851 - Z * 0.255038;
			var g = -X * 0.707196 + Y * 1.655397 + Z * 0.036152;
			var b =  X * 0.051713 - Y * 0.121364 + Z * 1.011530;

			r = (r <= 0.0031308) ? 12.92 * r : (1.0 + 0.055) * Math.pow(r, (1.0 / 2.4)) - 0.055;
			g = (g <= 0.0031308) ? 12.92 * g : (1.0 + 0.055) * Math.pow(g, (1.0 / 2.4)) - 0.055;
			b = (b <= 0.0031308) ? 12.92 * b : (1.0 + 0.055) * Math.pow(b, (1.0 / 2.4)) - 0.055;

			if (r > b & r > g) {
				// red is biggest
				if (r > 1.0) {
					g = g / r;
					b = b / r;
					r = 1.0;
				}
			}
			else if (g > b & g > r) {
				// green is biggest
				if (g > 1.0) {
					r = r / g;
					b = b / g;
					g = 1.0;
				}
			}
			else if (b > r & b > g) {
				// blue is biggest
				if (b > 1.0) {
					r = r / b;
					g = g / b;
					b = 1.0;
				}
			}
			return ([parseInt(r*255),parseInt(g*255),parseInt(b*255)]);
		}		
		
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
		
		function ctToRGB(kelvin) {
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
						//console.log(currentresult);
						A=ctToRGB(1000000/currentresult[4].state.ct);
						if (currentresult[4].state.reachable){
							$("#lamp4").css('border','1px solid');
						}else{
							$("#lamp4").css('border','1px solid red');
						}
						$("#lamp4_bri").html(parseInt((currentresult[4].state.bri/254)*100)+"%");
						current[4]={};
						current[4]['bri']=currentresult[4].state.bri;
						current[4]['rgbtext']='rgb('+A.r+','+A.g+','+A.b+')';
						current[4]['rgb']=[A.r,A.g,A.b];
						current[4]['ct']=parseInt(1000000/currentresult[4].state.ct);
						current[4]['reachable']=currentresult[4].state.reachable;
						current[4]['on']=currentresult[4].state.on==true;
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

						var x=(currentresult[5].state.xy[0]);
						var y=(currentresult[5].state.xy[1]);
						if (currentresult[5].state.reachable){
							$("#lamp5").css('border','1px solid');
						}else{
							$("#lamp5").css('border','1px solid red');
						}
						var rgb=xytorgb(x,y,1)
						current[5]={};
						current[5]['bri']=currentresult[5].state.bri;
						current[5]['rgbtext']='rgb('+ rgb.join(', ') +')';
						current[5]['rgb']=rgb;
						current[5]['xy']=[x,y];
						current[5]['reachable']=currentresult[5].state.reachable;
						current[5]['on']=currentresult[5].state.on==true;

						$("#lamp5_bri").html(parseInt((currentresult[5].state.bri/254)*100)+"%");
						$('#lamp5_hue').css('color','rgb('+ rgb.join(', ') +')');
						if (currentresult[5].state.on==true){
							$('#lamp5').css('background-color','rgb('+ rgb.join(', ') +')');
							$("#lamp5").html("AAN");
							if (isLight(rgb)){
								$('#lamp5').css('color','white');
							}else{
								$('#lamp5').css('color','black');
							}
							//console.log('rgb('+ HSV_RGB(h,s,v).join(', ') +')');
						}else{
							$("#lamp5").css('background-color',$("body").css('background-color'));
							$("#lamp5").css('color',$("body").css('color'));
							$("#lamp5").html("UIT");
						}
						//$('#lamp5').css('background-color', 'rgb('+B.r+','+B.g+','+B.b+')');
						//console.log(current);
					}		
				};
				xmlhttp.open("GET", 'Hue.php?Request=lights', true);
				xmlhttp.send();
				setTimeout(function(){ getCurrent(); }, 4000);
			}


			function getScenes(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
						$.each( currentresult, function( key, value ) {
						 //console.log( key + ": " + value.name );
						  scenes[value.name]=key;
						});
					}		
				};
				xmlhttp.open("GET", 'Hue.php?Request=scenes', true);
				xmlhttp.send();
			}
			
			function getGroups(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
						$.each( currentresult, function( key, value ) {
						  //console.log( key + ": " + value.name );
						  groups[value.name]=key;
						});
					}		
				};
				xmlhttp.open("GET", 'Hue.php?Request=groups', true);
				xmlhttp.send();
			}
			
			$( document ).ready(function()  {
				getCurrent();
				getScenes();
				getGroups();
			});
			
			
			function turnOnOff(element){
				if ($(element).html()=="AAN"){
					status=false;
				}else{
					status=true;
				}
				lamp=$(element).attr('id').substr(4);
				//console.log('HuePut.php?on='+status+"&light="+lamp);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'HuePut.php?action=lights&on='+status+"&light="+lamp, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
					}
				};
				xmlhttp.send();
			}
			
			function setScene(scene){
				var xmlhttp = new XMLHttpRequest();
				if (scene=="UIT"){
					xmlhttp.open("GET", 'HuePut.php?action=groups&scene='+"UIT"+"&group="+groups['Woonkamer'], true);
				}else{
					xmlhttp.open("GET", 'HuePut.php?action=groups&scene='+scenes[scene]+"&group="+groups['Woonkamer'], true);
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
					}
				};
				xmlhttp.send();
			}
			
			function selectCT(element){
				$("#CTModal").css("display","block");
				$("#CTModal").css("background-color",$("body").css("background-color"));
				$("#CTcolor").css("background-color",$(element).css("color"));
				$("#CTselect").val(current[parseInt(($(element).attr('id')).substr(4))]['ct']);
				$("#CTbutton").attr('id',"#CTbutton_"+parseInt(($(element).attr('id')).substr(4)));
			}
			
			function showCTVal(newval){
				var A=ctToRGB(newval);
				$('#CTcolor').css('background-color', 'rgb('+A.r+','+A.g+','+A.b+')');
				
			}
			
			function setCT(element){
				lamp=$(element).attr('id').substr(10);
				$(element).attr('id',"CTbutton");
				ct=parseInt(1000000/$("#CTselect").val());
				//console.log($("#CTselect").val());
				$("#CTModal").css("display","none");
				//console.log('HuePut.php?ct='+ct+"&on=true&light="+lamp);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'HuePut.php?action=lights&ct='+ct+"&on=true&light="+lamp, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
					}
				};
				xmlhttp.send();

			}
	
			function selectBri(element){
				$("#BriModal").css("display","block");
				$("#BriModal").css("background-color",$("body").css("background-color"));
				curval=parseInt(current[parseInt(($(element).attr('id')).substr(4))]['bri']);
				$("#Bri").css("background-color",'rgb('+curval+','+curval+','+curval+')');
				$("#Briselect").val(current[parseInt(($(element).attr('id')).substr(4))]['bri']);
				$("#Bributton").attr('id',"#Bributton_"+parseInt(($(element).attr('id')).substr(4)));
				$("#Bri").html(parseInt((curval/255)*100)+"%");
				if (curval<127){
					$('#Bri').css('color','white');
				}else{
					$('#Bri').css('color','black');
				}
			}
			
			function showBri(newval){
				$("#Bri").css("background-color",'rgb('+newval+','+newval+','+newval+')');
				$("#Bri").html(parseInt((newval/255)*100)+"%");
				if (newval<127){
					$('#Bri').css('color','white');
				}else{
					$('#Bri').css('color','black');
				}
			}
			
			function setBri(element){
				lamp=$(element).attr('id').substr(11);
				$(element).attr('id',"Bributton");
				bri=$("#Briselect").val();
				//console.log(bri);
				$("#BriModal").css("display","none");
				//console.log('HuePut.php?bri='+bri+"&on=true&light="+lamp);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'HuePut.php?action=lights&bri='+bri+"&on=true&light="+lamp, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
					}
				};
				xmlhttp.send();

			}

			function selectXY(element){
				$("#XYModal").css("display","block");
				$("#XYModal").css("background-color",$("body").css("background-color"));
				rgb=current[parseInt(($(element).attr('id')).substr(4))]['rgb'];
				document.getElementById('XYselect').jscolor.fromRGB(rgb[0],rgb[1],rgb[2]);
				$("#XYbutton").attr('id',"#XYbutton_"+parseInt(($(element).attr('id')).substr(4)));
				document.getElementById('XYselect').jscolor.show();
			}
			
			function setXY(element){
				hex=$("#XYselect").val();
				rgb=hextorgb(hex);
				xy=rgbtoxy(rgb[0],rgb[1],rgb[2]);
				lamp=$(element).attr('id').substr(10);
				$(element).attr('id',"XYbutton");
				//console.log('HuePut.php?action=lights&x='+xy[0]+'&y='+xy[1]+"&on=true&light="+lamp);
				$("#XYModal").css("display","none");
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET", 'HuePut.php?action=lights&x='+xy[0]+'&y='+xy[1]+"&on=true&light="+lamp, true);
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						//console.log(this.responseText);
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
					}
				};
				xmlhttp.send();
			}

			
		</script>
		<div id="Hue_mainframe">
			<div class="lamp_frame"><div class="hue_lamp noselect">Hanglamp</div><div class="hue_button noselect" onclick='turnOnOff(this)' id='lamp4'></div><div id="lamp4_hue" class="hue_button noselect" onclick="selectCT(this)">kleur</div><div id="lamp4_bri" class="hue_button noselect" onclick="selectBri(this)">helderheid</div></div>
			<div class="lamp_frame"><div class="hue_lamp">kleurenlamp</div><div class="hue_button noselect" onclick='turnOnOff(this)' id='lamp5'></div><div id="lamp5_hue" class="hue_button noselect" onclick="selectXY(this)">kleur</div><div id="lamp5_bri" class="hue_button noselect" onclick="selectBri(this)">helderheid</div></div>
			<div class="lamp_frame"><div class="hue_scene noselect" onclick="setScene('WK sfeer dim');">sfeer dim</div><div class="hue_scene noselect" onclick="setScene('WK sfeer 40');">sfeer 40%</div><div class="hue_scene noselect" onclick="setScene('WK sfeer 60');">sfeer 60%</div><div class="hue_scene noselect" onclick="setScene('WK sfeer 100');">sfeer 100%</div><div class="hue_scene noselect" onclick="setScene('WK dag 100');">dag 100%</div><div class="hue_scene noselect" onclick="setScene('UIT');">UIT</div></div>
		</div>
		
		<div id="CTModal" class="Huemodal"><div id="CTcolor"></div><input id="CTselect" type="range" min="2200" max="6500" value="0" oninput="showCTVal(this.value)"><button class="HueOK" id="CTbutton" onclick="setCT(this)">OK</button><div class="HueDesc">Selecteer kleurtemperatuur</div></div>
		
		<div id="BriModal" class="Huemodal"><div id="Bri"></div><input id="Briselect" type="range" min="0" max="255" value="0" oninput="showBri(this.value)"><button class="HueOK" id="Bributton" onclick="setBri(this)">OK</button><div class="HueDesc">Selecteer helderheid</div></div>
		
		<div id="XYModal" class="Huemodal"><div id="XYcolor"></div><input id="XYselect" style="display:none" class="jscolor {position:'top', mode:'HS', width:294, height:180, padding:0}"><button class="HueOK" id="XYbutton" onclick="setXY(this)">OK</button><div class="HueDesc">Selecteer kleur</div></div>
		
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
