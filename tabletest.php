<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Test</title>
		<meta name="description" content="Test page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/teststyle.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
			<!--
		
		function clickButton(elem) {
				var myid=($(elem).attr('id')).split('_');
				switch (myid[0]) {
					case "add":
						alert("add row to table " + myid[1]);
					break;
					case "modify":
						var sql_id=$("#" + myid[2] + "_0").html();
						alert("modify row with ID " +sql_id + " in table " + myid[1]);
					break;
					case "delete":
						var sql_id=$("#" + myid[2] + "_0").html();
						alert("delete row with ID " + sql_id + " in table " + myid[1]);
					break;
				}
				
			}
		//-->
		</script>
</head>
<body>

<?php
  include 'includes/db_connect.php';

/* connect to the db */

/* show tables */
$result = mysql_query('SHOW TABLES');
while($tableName = mysql_fetch_row($result)) {

	$table = $tableName[0];
	
	echo '<h3>',$table,'</h3>
	';
	$result2 = mysql_query('SHOW COLUMNS FROM '.$table) or die('cannot show columns from '.$table);
	$result3 = mysql_query('SELECT * FROM '.$table) or die('cannot show contents from '.$table);
	if(mysql_num_rows($result2)) {
		echo '
		<table cellpadding="0" cellspacing="0" class="db-table" id="'.$table.'">';
		echo '<tr>';
		$x=-1;
		while($row2 = mysql_fetch_row($result2)) {
			$x++;
			echo "<th>" . $row2[0] . "</th>";
			$fields[$x]=$row2[0];
		}
		echo '<th></th><th id="add_' . $table . '" class="add" onmouseup="clickButton(this)">add row</th></tr>
		';
		$rows=-1;
		while($row3 = mysql_fetch_row($result3)) {
			echo '<tr>';
			$rows++;
			for ($i=0;$i<=$x;$i++){
				echo '<td id="' . $rows . "_" . $i . '">';
				echo $row3[$i];
				echo '</td>';
			}
			echo '<td id="modify_' . $table . '_' . $rows . '" class="modify" onmouseup="clickButton(this)">modify</td>
			<td id="delete_' . $table . '_' . $rows . '" class="delete" onmouseup="clickButton(this)">delete</td></tr>';
		}
		echo '</table><br />';
	}
}

?>
</body>