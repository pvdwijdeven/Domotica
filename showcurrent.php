<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
	   <link rel="icon" href="favicon.ico?v1" type="image/x-icon" />
	   <link rel="shortcut icon" href="favicon.ico?v1" type="image/x-icon" />
	   <link rel="apple-touch-icon" href="/measurement.png"/>
	   <link rel="apple-touch-icon-precomposed" href="/measurement.png"/> 
		<title>Current Measurements</title>	
		<link rel="stylesheet" type="text/css" href="style.css">
   <title>24 hours overview graph</title>
   <script src="https://www.google.com/jsapi"></script>
   <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
   <script src="https://jquery-csv.googlecode.com/files/jquery.csv-0.71.js"></script>
   <script type='text/javascript'>
   // load the visualization library from Google and set a listener
   google.load("visualization", "1", {packages:["corechart"]});
   google.setOnLoadCallback(drawChartfromCSV);
  
   function drawChartfromCSV(){
     // grab the CSV
         $.get("/DB/24hrs.csv", function(csvString) {
         // transform the CSV string into a 2-dimensional array
            var arrayData = $.csv.toArrays(csvString, {onParseValue: $.csv.hooks.castToScalar});
         // this new DataTable object holds all the data
            var data = new google.visualization.arrayToDataTable(arrayData);
         // this view can select a subset of the data at a time
            var view = new google.visualization.DataView(data);
            view.setColumns([7,2,9]);
         var options = {
         title: "24 hours overview",
         curveType: 'function',
         backgroundColor: '#CCCCCC',
          hAxis: {title: data.getColumnLabel(7), minValue: data.getColumnRange(7).min, maxValue: data.getColumnRange(7).max, showTextEvery: 3},
          vAxes: {
             0: {logScale: false, minValue: data.getColumnRange(2).min, maxValue: data.getColumnRange(1).max, format: '##.0 degC', gridlines: {color: '#000000'}},
             1: {logScale: false, minValue: data.getColumnRange(1).min, maxValue: data.getColumnRange(2).max, format: '####', gridlines: {color: '#000000'}},
             gridlines: {color: '#000000'}
          },
		  legend: {position: 'top'},
          series:{
		0:{targetAxisIndex:0, color:"#0000CD"},
		1:{targetAxisIndex:0, color:"#00BFFF"}}};
          var chart = new google.visualization.ComboChart(document.getElementById('csv2chart'));
          chart.draw(view, options);


            var view2 = new google.visualization.DataView(data);
            view2.setColumns([7,3,10,4,11]);
         var options2 = {
         title: "24 hours overview",
         curveType: 'function',
         areaOpacity: 0.1,
         backgroundColor: '#CCCCCC',
          hAxis: {title: data.getColumnLabel(7), minValue: data.getColumnRange(7).min, maxValue: data.getColumnRange(7).max, showTextEvery: 3, format: 'HH:mm'},
          vAxes: {
             0: {logScale: false, minValue: data.getColumnRange(4).min, maxValue: data.getColumnRange(4).max, format: '##.0', gridlines: {color: '#000000'}},
             1: {logScale: false, minValue: data.getColumnRange(3).min, maxValue: data.getColumnRange(3).max, format: '####', gridlines: {color: '#000000'}},
             gridlines: {color: '#000000'}
          },
		  legend: {position: 'top'},
          series:{
		0:{targetAxisIndex:1, type : "line", color:"#0000CD"},
		1:{targetAxisIndex:1, type : "line", color:"#00BFFF"}, 
		2:{targetAxisIndex:0, type : "line", color:"#FF0000"},
		3:{targetAxisIndex:0, type : "line", color:"#F08080"}}};
          var chart2 = new google.visualization.ComboChart(document.getElementById('csv2chart2'));
          chart2.draw(view2, options2);

		  });
   }
   </script>
   </head>
	<body>
<?php
$db = new SQLite3('DB/measureDB.sqlite3') or die('Unable to open database');
$result = $db->query('SELECT * FROM [valtable] order by [date] DESC LIMIT 1');
$row = $result->fetchArray(SQLITE3_ASSOC);

print("		<div class = 'tCurrent'>");
print("			<div class = 'titleText'>Last measurement: "."<BR>"."{$row['date']}<BR><BR></div>");

$tempIn = $row['temp2'];
$tempOut = round($row['outtemp']-273.15,1); 
$pressIn = $row['press'] *  1000;
$pressOut = $row['outpress'];
$humIn = $row['hum'];
$humOut = $row['outhum'];

print("		<div class = 'tColumn'>
				<div class = 'tCel'></div>
				<div class = 'tCel'><a href='tempgraph.html'>Temperature</a> : </div>
				<div class = 'tCel'><a href='pressgraph.html'>Pressure</a> : </div>
				<div class = 'tCel'><a href='humgraph.html'>Humidity</a> : </div>
			</div>
			<div class = 'tColumn'>
				<div class = 'tCel'>Inside</div>
				<div class = 'tCel'>{$tempIn}</div>
				<div class = 'tCel'>{$pressIn}</div>
				<div class = 'tCel'>{$humIn}</div>
			</div>
			<div class = 'tColumn'>
				<div class = 'tCel'></div>
				<div class = 'tCel'>°C</div>
				<div class = 'tCel'>hPa</div>
				<div class = 'tCel'>%</div>
			</div>
			<div class = 'tColumn'>
				<div class = 'tCel'>Outside</div>
				<div class = 'tCel'>{$tempOut}</div>
				<div class = 'tCel'>{$pressOut}</div>
				<div class = 'tCel'>{$humOut}</div>
			</div>
			<div class = 'tColumn'>
				<div class = 'tCel'></div>
				<div class = 'tCel'>°C</div>
				<div class = 'tCel'>hPa</div>
				<div class = 'tCel'>%</div>
			</div>");
?>
			<br>
			<a class='nolink' href='forecast.php'><div class="navButton">Forecast</div></a>
			<a class='nolink' href='overview.php'><div class="navButton">Total overview</div></a><br>
			<div id="csv2chart" style="align: center; width: auto; height: 500px;"> </div>
			<a class='nolink' href='forecast.php'><div class="navButton">Forecast</div></a>
			<a class='nolink' href='overview.php'><div class="navButton">Total overview</div></a><br>

			<div id="csv2chart2" style="align: center; width: auto; height: 500px;"> </div>
			<a class='nolink' href='forecast.php'><div class="navButton">Forecast</div></a>
			<a class='nolink' href='overview.php'><div class="navButton">Total overview</div></a><br>
		</div>
	</body>
</html>