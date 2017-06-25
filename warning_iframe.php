<?php 
	$dummy=$_SERVER;
	$title="Domo warnings_iframe";
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
		<link rel="stylesheet" href="css/warning.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	</head>
	<body>
		<script>
		
		
			function openDet() {
				getraspstat();
				$("#showdetails").css('width', $(warning_mainframe).css('width'));
				$("#showdetails").css('height', $(warning_mainframe).css('height'));
				setTimeout(function(){closeDet(); }, 60000);
			}
		
			function closeDet() {
				document.getElementById("showdetails").style.width = "0";
				document.getElementById("showdetails").style.height = "0";
			}

			function addLog(text, setter="", button=false){
				//console.log(button);
				if (!button){
					$("#warning_mainframe").append("<div class='warning' id='"+setter+"'>"+text+"</div>");
				}else{
					$("#warning_mainframe").append("<div class='warning' id='"+setter+"'>"+text+"</div><button id='button_"+setter+"' class='warning_button' onclick='confirmLog(&quot;"+setter+"&quot;)'>gezien</button>");
				}
			}
			
			
			function confirmLog(setter){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						$("#"+setter).remove();
						$("#button_"+setter).remove();
					}
				};
				if (setter!="diskfree"){
					xmlhttp.open("GET", "putToCurrent.php?"+setter+"=0", true);
				}else{
					xmlhttp.open("GET", "putToCurrent.php?"+setter+"=100", true);
				}
				xmlhttp.send();
			}

			function removeLog(setter){
				$("#"+setter).remove();
			}
			
			function getValues1(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						if (currentresult==2){
							if (!$( "#wasmachine" ).length){
								addLog("wasmachine is klaar","wasmachine",true);
							}
						}
						if (currentresult==1){
							if (!$( "#wasmachine" ).length){
								addLog("wasmachine draait","wasmachine",false);
							}
						}					}
				};
				xmlhttp.open("GET", "getFromCurrent.php?request=wasmachine", true);
				xmlhttp.send();
			}

		
			function getValues2(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						if (currentresult==1){
							if (!$( "#sabotagehal" ).length){
								addLog("sabotage bewegingsmeler hal","sabotagehal",true);
							}
						}
					}
				};
				xmlhttp.open("GET", "getFromCurrent.php?request=sabotagehal", true);
				xmlhttp.send();
			}

			function getValues3(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						if (parseInt(currentresult)<=20){
							if (!$( "#diskfree" ).length){
								addLog("Lage diskruimte: "+parseInt(currentresult)+"%","diskfree",true);
							}
						}
					}
				};
				xmlhttp.open("GET", "getFromCurrent.php?request=diskfree", true);
				xmlhttp.send();
			}

			function getValues4(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						if (parseInt(currentresult)>=70){
							if (!$( "#CPUtemp" ).length){
								addLog("CPU temperatuur te hoog: "+parseInt(currentresult)+"&degC","CPUtemp",true);
							}
						}
					}
				};
				xmlhttp.open("GET", "getFromCurrent.php?request=CPUtemp", true);
				xmlhttp.send();
			}

			function getValues5(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						if (parseInt(currentresult)>=80){
							if (!$( "#CPUmax" ).length){
								addLog("CPU load te hoog: "+parseInt(currentresult)+"%","CPUmax",true);
							}
						}
					}
				};
				xmlhttp.open("GET", "getFromCurrent.php?request=CPUmax", true);
				xmlhttp.send();
			}
			
			function getHue(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						for (x=1;x<=Object.keys(currentresult).length;x++){
							if (!currentresult[x].state.reachable){
								if (!$( "#" + currentresult[x].name).length){
									addLog("Lamp "+currentresult[x].name + " niet bereikbaar",currentresult[x].name,false);
								}
							}else{
								removeLog(currentresult[x].name);
							}
						}		
					}
				
				};
				xmlhttp.open("GET", 'Hue.php?Request=lights', true);
				xmlhttp.send();
			}
			
			function allOK(){
				if ($("#warning_mainframe div").length<=2){
					$("#all_ok").show();
				}else{
					$("#all_ok").hide();
				}
			}

			function getraspstat(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						$("#detail_text").html(currentresult);
					}
				};
				xmlhttp.open("GET", "raspstat.php", true);
				xmlhttp.send();
			}

			
			$( document ).ready(function()  {
				getValues1();
				getValues2();
				getValues3();
				getValues4();
				getValues5();
				getHue();
				allOK();
				setInterval(getValues1, 5000);
				setInterval(getValues2, 5000);
				setInterval(getValues3, 600000);
				setInterval(getValues4, 600000);
				setInterval(getValues5, 600000);
				setInterval(getHue, 5000);
				setInterval(allOK, 5000);
				
			});

		</script>
		<div id="warning_mainframe">
		<div class="warning_header" onclick="openDet()">Meldingen</div>
			<div class="warning_none" id="all_ok">Alles is OK!</div>
		</div>
		<div id="showdetails" class="warning_details" onclick="closeDet()">
		<div id="detail_text"></div>
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
