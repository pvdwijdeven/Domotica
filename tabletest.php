<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Test</title>
		<meta name="description" content="Test page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/teststyle.css?v=1.0">
</head>
<body>

<?php
  include 'includes/db_connect.php';

/* connect to the db */

/* show tables */
$result = mysql_query('SHOW TABLES');
while($tableName = mysql_fetch_row($result)) {

	$table = $tableName[0];
	
	echo '<h3>',$table,'</h3>';
	$result2 = mysql_query('SHOW COLUMNS FROM '.$table) or die('cannot show columns from '.$table);
	$result3 = mysql_query('SELECT * FROM '.$table) or die('cannot show contents from '.$table);
	if(mysql_num_rows($result2)) {
		echo '<table cellpadding="0" cellspacing="0" class="db-table">';
		echo '<tr>';
		$x=-1;
		while($row2 = mysql_fetch_row($result2)) {
			$x++;
			echo "<th>" . $row2[0] . "</th>";
			$fields[$x]=$row2[0];
		}
		echo '</tr>';
		while($row3 = mysql_fetch_row($result3)) {
			echo '<tr>';
			for ($i=0;$i<=$x;$i++){
				echo '<td>';
				echo $row3[$i];
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table><br />';
	}
}

?>
</body>