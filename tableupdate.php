<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Update table</title>
		<meta name="description" content="Test page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/teststyle.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		
		<script type="text/javascript">
			<!--
		
		function clickCancel() {
			document.location.href = 'tabletest.php?action=none';
			}
		//-->
		</script>
		
</head>
<body>

<?php
  include 'includes/db_connect.php';
  $table = $_GET['table'];
  $action = $_GET['action'];
  if ($action!="add"){
	$row = $_GET['ID'];
	$result3 = mysql_query('SELECT * FROM '.$table. ' WHERE ID=' .$row) or die('cannot show contents from '.$table);
	$row3 = mysql_fetch_row($result3);
  };
  
  $result2 = mysql_query('SHOW COLUMNS FROM '.$table) or die('cannot show columns from '.$table);
	if(mysql_num_rows($result2)) {
		echo '
		<form method="POST" action="tabletest.php">';
		$x=-1;
		while($row2 = mysql_fetch_row($result2)) {
			$x++;
			echo "<table><tr><td class='name'>" . $row2[0] . ":</td><td class='type'>" . $row2[1] . "</td></tr></table>";
			switch($action){
				case "modify":
				if (substr($row2[0], -2)=="ID" and strlen($row2[0])>2)
					{ $resultID = mysql_query('SELECT * FROM ' . lcfirst($row2[0])) or die('cannot show columns from '.lcfirst($row2[0]));
					echo "<select name='" . $row2[0] . "'>";
					while($rowID = mysql_fetch_row($resultID)) {
						if ($rowID[0]==$row3[$x]) {$selected="selected='selected' ";} else {$selected="";}
						echo "<option ".$selected."value='".$rowID[0]."'>" .$rowID[1]."</option>";
					}
					echo "</select>";
				} else {
						echo "<input class='fieldupdate' type='text' ID='" . $row2[0] . "' name='" . $row2[0] . "' value='". $row3[$x] . "'></input>
						";
				}
				break;
				case "delete":
				if (substr($row2[0], -2)=="ID" and strlen($row2[0])>2)
					{ $resultID = mysql_query('SELECT * FROM ' . lcfirst($row2[0])) or die('cannot show columns from '.lcfirst($row2[0]));
					while($rowID = mysql_fetch_row($resultID)) {
						if ($rowID[0]==$row3[$x]) {	echo "<input  class='fieldupdate' type='text' ID='" . $row2[0] . "' name='" . $row2[0] . "' value='". $rowID[1] . "' readonly></input>
						";
						}
					}
				} else {
					echo "<input class='fieldupdate' type='text' ID='" . $row2[0] . "' name='" . $row2[0] . "' value='". $row3[$x] . "' readonly></input>
					";
				}
				break;
				case "add":
				if (substr($row2[0], -2)=="ID" and strlen($row2[0])>2)
					{ $resultID = mysql_query('SELECT * FROM ' . lcfirst($row2[0])) or die('cannot show columns from '.lcfirst($row2[0]));
					echo "<select name='" . $row2[0] . "'>";
					while($rowID = mysql_fetch_row($resultID)) {
						echo "<option value='".$rowID[0]."'>" .$rowID[1]."</option>";
					}
					echo "</select>";
				} else {
					echo "<input class='fieldupdate' type='text' ID='" . $row2[0] . "' name='" . $row2[0] . "'</input>
					";
				}
				break;
			}				
			$fields[$x]=$row2[0];
		}
		echo '
		<input type="hidden" name="action" value="' .$action. '">
		<input type="hidden" name="tablename" value="' .$table. '">
		<input type="submit" value="' .$action.'">
      <input type="button" value="cancel" onmouseup="clickCancel()">
		</form>
		';
	}
?>
<script type="text/javascript">
			<!--
$("#ID").attr('readonly', true);
-->
</script>

</body>
</html>
