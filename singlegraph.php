  <?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
// The Chart table contains two fields: weekly_task and percentage
// This example will display a pie chart. If you need other charts such as a Bar chart, you will need to modify the code a little to make it work with bar chart and other charts

$to=time();
$from = $to-2*24*60*60;
$count=1;
if (!empty($_GET['every'])){
$count=$_GET['every'];}

$val='val1';
if (!empty($_GET['value'])){
$val=$_GET['value'];}

$sqlstring="set @row:=-1";
$sqlstring2="SELECT measurement_values.* FROM measurement_values INNER JOIN (SELECT ID FROM (SELECT @row:=@row+1 AS rownum, ID FROM ( SELECT ID from measurement_values WHERE ". $val . " is not null and ID BETWEEN ".$from." AND ".$to." ORDER BY ID ) AS sorted ) as ranked WHERE rownum %" . $count." = 0 ) AS subset ON subset.ID = measurement_values.ID";
$sth = mysql_query($sqlstring);
$sth = mysql_query($sqlstring2);

/*
---------------------------
example data: Table (Chart)
--------------------------
weekly_task     percentage
Sleep           30
Watching Movie  40
work            44
*/

$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'date/time', 'type' => 'datetime'),
    array('label' => $val, 'type' => 'number')

);

$rows = array();
while($r = mysql_fetch_assoc($sth)) {
    $temp = array();
    // the following line will be used to slice the Pie chart
	
    $temp[] = array('v' => "Date(". ((int) $r['ID'])*1000 . ")" ); 

    // Values of each slice
    $temp[] = array('v' => (int) $r[$val]); 
    $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

?>

<html>
  <head>
    <!--Load the Ajax API-->
	<style> html {height: 100%;} </style>
	<link rel="stylesheet" href="css/style.css?v=1.0">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">


	
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var options = {
           title: 'Trend',
		   curveType: 'function',
		   crosshair: { trigger: 'both' },
		   explorer: { axis: 'horizontal', keepInBounds: true, maxZoomIn: .001, maxZoomOut:1000 },
		   backgroundColor: 'lightsteelblue',
		   chartArea:{left:50,top:50,width:'80%',height:'80%'},
		   hAxis: {gridlines: {color: 'grey'}, format: 'yyyy-MM-dd HH:mm:ss'},
		   vAxis: {gridlines: {color: 'grey'}}
        };
      // Instantiate and draw our chart, passing in some options.
      // Do not forget to check your div ID
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
	
	 window.onresize = drawChart;
	
    </script>
  </head>

  <body>
	<div>menu comes here...</div>
	<div id="chart_div" style="width: 100%; height: 100%;"></div>
  </body>
</html>


<?php
  