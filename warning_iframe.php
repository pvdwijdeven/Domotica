<?php 
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
			function addLog(text, setter="", button=false){
				console.log(button);
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
				xmlhttp.open("GET", "putToCurrent.php?"+setter+"=0", true);
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
						if (currentresult==1){
							if (!$( "#wasmachine" ).length){
								addLog("wasmachine is klaar","wasmachine",true);
							}
						}
					}
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
			
			$( document ).ready(function()  {
				getValues1();
				getValues2();
				getHue();
				allOK();
				setInterval(getValues1, 5000);
				setInterval(getValues2, 5000);
				setInterval(getHue, 5000);
				setInterval(allOK, 5000);
				
			});

		</script>
		<div id="warning_mainframe">
		<div class="warning_header">Meldingen</div>
			<div class="warning_none" id="all_ok">Alles is OK!</div>
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
