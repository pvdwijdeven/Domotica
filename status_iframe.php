<?php 
	$title="Domo status_iframe";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	if (array_key_exists ('HTTP_REFERER',$_SERVER)){
		$loginchecked=true;
	}else{
		$loginchecked=login_check($mysqli);
	};
	
	if (!$adminpage AND $loginchecked){
		
		
	$result = mysql_query('SELECT * FROM measurements WHERE TileID=1');
	$i = -1;
	while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
		$i += 1;
		$IDs[$i]          = $row['ID'];
		$ValueNumbers[$i] = $row['ValueNumber'];
	}
	$IDs          = implode(",", $IDs);
	$ValueNumbers = implode(",", $ValueNumbers);


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
		<link rel="stylesheet" href="css/status.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	</head>
	<body>
		<script>
			function getValues(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						currentresult = currentresult.split(',');
						var IDs= "<?php echo $IDs;?>";
						var valuepos = "<?php echo $ValueNumbers;?>";
						IDs=IDs.split(',');
						valuepos=valuepos.split(',');
						$("#temperatuur").html(currentresult[valuepos[0]]+"&degC");
						$("#lucht").html(currentresult[valuepos[3]]+"%");
					}
				};
				xmlhttp.open("GET", "getCurrentValues.php", true);
				xmlhttp.send();
			}
			
			function getTimeDate(){
				Date.prototype.getWeek = function() {
					var onejan = new Date(this.getFullYear(), 0, 1);
					return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
				}
				var weekday = new Array(7);
				weekday=["Zondag","Maandag","Dinsdag","Woensdag","Donderdag","Vrijdag","Zaterdag"];
				month=["januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"];
				var d = new Date();
				datum=d.toLocaleDateString();
				dag=d.getDate();
				maand=month[d.getMonth()];
				jaar=d.getFullYear();
				tijd=d.toLocaleTimeString();
				dagnaam=weekday[d.getDay()];
				week=d.getWeek();
				$("#tijd").html(tijd);
				$("#datum").html(dagnaam+" "+dag+" "+maand+" "+jaar);
				$("#week").html("week "+week);
				while (parseInt($("#datum").css("height"))>35){
					curfont = parseInt($("#datum").css("font-size"));
					curfont-=1;
					$("#datum").css("font-size",curfont+"px");
				}

			}
			
			$( document ).ready(function()  {
				getValues();
				getTimeDate();
				setInterval(getValues, 300000);
				setInterval(getTimeDate, 1000);
			});

		</script>
		<div id="status_mainframe"><table id="status_table">
		<tr><td colspan='2'><div id="tijd"></div></td></tr>
		<tr><td colspan='2'><div id="datum"></div></td></tr>
		<tr><td colspan='2'><div id="week"></div></td></tr>
		<tr class="top_border"><td class="top_border">luchtvochtigheid</td><td class="top_border">temperatuur</td></tr>
		<tr><td><div id="lucht"></div></td><td><div id="temperatuur"></div></td></tr>
		</table>
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
