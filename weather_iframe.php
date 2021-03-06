<?php 
	$dummy=$_SERVER;
	$title="Domo Weather_iframe";
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
			function getIcon(iconname){
				switch(iconname){
				case 'clear':
					icon="01";
					break;
				case 'mostlysunny':
				case 'partlycloudy':
					icon="02";
					break;
				case 'mostlycloudy':
				case 'partlysunny': 
					icon="03";
					break;
				case 'cloudy':
					icon="04";
					break;
				case 'chancerain':
					icon="09";
					break;
				case 'rain':
					icon="10";
					break;
				case 'chancetstorms':
				case 'tstorms':
				case 'unknown':
					icon="11";
					break;
				case 'sleet':
				case 'snow':
				case 'flurries':
				case 'chancesleet':
				case 'chancesnow':
				case 'chanceflurries':
					icon="12";
					break;
				default:
					icon="50";
				}
				icon="resources/weather/" +icon+ "d.png";
				return icon;
			}
		
			function getCurrent(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = JSON.parse(this.responseText);
						//console.log(currentresult);
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
						//console.log(forecastresult);
						processForecast(forecastresult);
					}
				};
				xmlhttp.open("GET", "weather.php?status=forecast", true);
				xmlhttp.send();
			}

			function getHourly(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var hourlyresult = JSON.parse(this.responseText);
						//console.log(hourlyresult);
						processHourly(hourlyresult);
					}
				};
				xmlhttp.open("GET", "weather.php?status=hourly", true);
				xmlhttp.send();
			}
			
			$( document ).ready(function()  {
				getCurrent();
				getForecast();


		//		if (window.self == window.top){
		//			setTimeout(function(){ window.location.href = "domo_dashboard.php"; }, 60000);
		//		}
			});
			
			// Load the Visualization API and the piechart package.
			google.load('visualization', '1', {'packages':['corechart']});

			// Set a callback to run when the Google Visualization API is loaded.
			
			function processCurrent(current){
				$("#weather_icon").html('<img src="resources/weather/'+current.weather[0].icon+'.png" width="150px" height="96px">');
				$("#weather_temp").html("<div class='emphasize'>"+Math.round(current.main.temp)+"&degC&nbsp</div>");
				$("#weather_current").html("luchtvochtigheid: "+current.main.humidity+"%<BR>"+current.weather[0].description);
				var sunr=current.sys.sunrise;
				var suns=current.sys.sunset;
				var d = new Date();
				var curepoch = Math.round(d.getTime() / 1000);
				//if (curepoch<sunr || curepoch>suns){
					$('#weather_icon').css("background-color", "black");
					$('#weather_temp').css("background-color", "black");
					$('#weather_temp').css("color", "white");
					for (x=1;x<5;x++){
						$('#forecast'+x).css("background-color", "black");
					}
				//}else{
				//	$('#weather_icon').css("background-color", "SkyBlue ");
				//	$('#weather_temp').css("background-color", "SkyBlue ");
				//	$('#weather_temp').css("color", "black");
				//	for (x=1;x<5;x++){
				//		$('#forecast'+x).css("background-color", "SkyBlue");
				//	}					
				//}
				
				setTimeout(function(){ getCurrent(); }, 300000);		
			}

			function processForecast(jsforecast){
					$('#weather_minmax').html("max:"+jsforecast.forecast.simpleforecast.forecastday[0].high.celsius+"&deg<BR>min:"+jsforecast.forecast.simpleforecast.forecastday[0].low.celsius+"&deg");
					for (x=0;x<4;x++){
						$("#forecast"+(x+1)).html('<img src="'+getIcon(jsforecast.forecast.simpleforecast.forecastday[x].icon)+'" width="70px" height="40px">');
						$("#temp"+(x+1)).html(jsforecast.forecast.simpleforecast.forecastday[x].low.celsius+"&deg/"+jsforecast.forecast.simpleforecast.forecastday[x].high.celsius+"&deg");
						$("#day"+(x+1)).html(jsforecast.forecast.simpleforecast.forecastday[x].date.weekday.substring(0,2));
					}
				setTimeout(function(){ getForecast(); }, 3600000);		
			}
			
			function processHourly(jshourly){
				var hforecast=[];
				
				for (x=0;x<jshourly.hourly_forecast.length;x++){
					var d=new Date();
					d.setTime(jshourly.hourly_forecast[x].FCTTIME.epoch*1000);
					hforecast.push([d,parseInt(jshourly.hourly_forecast[x].temp.metric),parseInt(jshourly.hourly_forecast[x].pop)]);
				}
				//console.log(hforecast);
				var data = new google.visualization.DataTable();
				data.addColumn('datetime', 'datum/tijd');
				data.addColumn('number', 'temp.');
				data.addColumn('number', 'neerslag%');
				data.addRows(hforecast);
				 // Set chart options
				var options = {'title':'Komende 30 uur:',
                     'width':293,
					 connectSteps:false,
					 areaOpacity:1,
					 hAxis:{textStyle: {color: $('body').css("color")}},
					 curveType:'function',
                     'height':146,
					 'legend':{position:'top', textStyle: {color: $('body').css("color")}},
					 backgroundColor: $('body').css("background-color"),
					 series:{
					1:{targetAxisIndex:1, type:"area", color:"lightblue", lineWidth:0},
					0:{targetAxisIndex:0, type: "line", color:"red"}},
					vAxes:{
						1:{minValue:0, maxValue:100, textStyle: {color: $('body').css("color")}},
						0:{textStyle: {color: $('body').css("color")}}
					}
				};

			  // Instantiate and draw our chart, passing in some options.
			  var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
			  chart.draw(data, options);
				return true
			}	

			window.onclick = function(event) {
				//console.log(event.target.parentElement);
				if ($(event.target.parentElement).attr('ID')=='weather_icon'){
					if (!$('.weather_modal1').is(":visible")){
						$('.weather_modal1').show();
						getHourly();
						setTimeout(function(){ $('.weather_modal1').hide(); }, 60000);	
					}else{
						$('.weather_modal1').hide();
					}
				}
			}			
			
		</script>
		<div id="weather_mainframe">
		<div id="weather_main">
			<table id="weather"><tr><td colspan='2' id="weather_icon"></td><td colspan='2' id="weather_temp"></td></tr>
			<tr><td colspan='3' id="weather_current"></td><td id="weather_minmax"></td></tr>
			<tr class="forecast"><td id="day1"></td><td id="day2"></td><td id="day3"></td><td id="day4"></td></tr>
			<tr class="forecast"><td id="forecast1"></td><td id="forecast2"></td><td id="forecast3"></td><td id="forecast4"></td></tr>
			<tr class="forecast"><td id="temp1"></td><td id="temp2"></td><td id="temp3"></td><td id="temp4"></td></tr>
			</table>

		</div>
		
		<div id="weather_detail" class="weather_modal1">
			<table id="weather">
				<td><td><div id="chart_div"></div></td></tr>
			</table>
		</div>
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
