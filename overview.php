<!DOCTYPE html>
<head>
<?php
include 'createtotal.php';
?>
   <title>Total overview graph</title>
		<link rel="stylesheet" type="text/css" href="style.css">
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="favicon.ico?v1" type="image/x-icon" />
   <link rel="shortcut icon" href="favicon.ico?v1" type="image/x-icon" />
   <link rel="apple-touch-icon" href="measurement.png"/>
   <link rel="apple-touch-icon-precomposed" href="measurement.png"/> 
   <script src="https://www.google.com/jsapi"></script>
   <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
   <script src="https://jquery-csv.googlecode.com/files/jquery.csv-0.71.js"></script>
   <script type='text/javascript'>
   // load the visualization library from Google and set a listener
   google.load("visualization", "1", {packages:["corechart"]});
   google.setOnLoadCallback(drawChartfromCSV);
  
   function drawChartfromCSV(){
     // grab the CSV
         $.get("/total.csv", function(csvString) {
         // transform the CSV string into a 2-dimensional array
            var arrayData = $.csv.toArrays(csvString, {onParseValue: $.csv.hooks.castToScalar});
         // this new DataTable object holds all the data
            var data = new google.visualization.arrayToDataTable(arrayData);
         // this view can select a subset of the data at a time
            var view = new google.visualization.DataView(data);
            view.setColumns([0,1,2,3,4]);
         var options = {
         title: "Total overview",
         curveType: 'function',
		 backgroundColor: '#CCCCCC',
         chartArea: {left:120, bottom: 400},
          hAxis: {title: "date/time", minValue: data.getColumnRange(0).min, maxValue: data.getColumnRange(0).max},
          vAxes: {
             0: {logScale: false, minValue: data.getColumnRange(4).min, maxValue: data.getColumnRange(4).max, format: '##.0', gridlines: {color: '#000000'}},
             1: {logScale: false, minValue: data.getColumnRange(3).min, maxValue: data.getColumnRange(3).max, format: '####', gridlines: {color: '#000000'}}
          },
		  legend: {position: 'top'},
          series:{
		0:{targetAxisIndex:0},
		1:{targetAxisIndex:0},
		2:{targetAxisIndex:1, format: '####'},
		3:{targetAxisIndex:0}}};
//          vAxis: {title: "Temperature [degC]", minValue: data.getColumnRange(2).min, maxValue: data.getColumnRange(2).max},
//         legend: 'none'
//         };
          var chart = new google.visualization.LineChart(document.getElementById('csv2chart'));
          chart.draw(view, options);
         });
   }
   </script>
</head>
<body>
	<div class = 'tCurrent'>
		<div class = 'titleText'>Measurement history</div>
		<br>
		<a class='nolink' href='showcurrent.php'><div class="navButton">Back</div></a><br>
		<div id="csv2chart" style="align: center; width: auto; height: 500px;"> </div>
		<br>
		<a class='nolink' href='showcurrent.php'><div class="navButton">Back</div></a>
</body>
