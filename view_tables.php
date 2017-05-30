<?php 
	$title="view/modify tables";
	$adminpage=true;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">
			<!--
			
				window.onclick = function(event) {
					if (event.target == document.getElementById("mySidenav")) {
					document.getElementById("mySidenav").style.width = "0";
					}
				}	
				
				function openNav() {
					document.getElementById("mySidenav").style.width = "100%";
				}
			
				function closeNav() {
					document.getElementById("mySidenav").style.width = "0";
				}
				
				function clickButton(elem) {
					var myid=($(elem).attr('id')).split('_');
					switch (myid[0]) {
						case "add":
							document.location.href = 'tableupdate.php?action=add&table=' + myid[1];
						break;
						case "modify":
							var sql_id=$("#" + myid[1] + "_" + myid[2] + "_0").html();
							document.location.href = 'tableupdate.php?action=modify&table=' + myid[1] + '&ID=' + sql_id;
						break;
						case "delete":
							var sql_id=$("#" + myid[1] + "_" + myid[2] + "_0").html();
							document.location.href = 'tableupdate.php?action=delete&table=' + myid[1] + '&ID=' + sql_id;
						break;
					}
					
				}
			
			
				//-->
		</script>
		<?php include "includes/header.php"; ?>
		<!-- main page starts here -->
		<div class="buttonarea">
			<?php
				if (!empty($_POST)) {
					switch($_POST['action']){
						case "add":
						$vals="";
						$fields="";
						foreach ($_POST as $key => $value)
						{
							if($key!='action' and $key!='ID' and $key!='tablename'){
								$vals=$vals .", '".$value."'";
								$fields=$fields.', '.$key;
							}
						};
						$vals = substr($vals, 1);
						$fields = substr($fields, 1);
						$sqlstring="INSERT INTO " . $_POST['tablename'] . ' (' . $fields . ') VALUES (' . $vals . ');'; 
						break;
						case "modify":
						$vals="";
						foreach ($_POST as $key => $value)
						{
							if($key!='action' and $key!='ID' and $key!='tablename'){
								$vals=$vals .', '.$key . "='" . $value . "' ";
							}
						};
						$vals = substr($vals, 1);
						$sqlstring="UPDATE " . $_POST['tablename'] . " SET " . $vals . " WHERE ID = '" . $_POST['ID']. "';";
						break;
						case "delete":
						$sqlstring="DELETE FROM " . $_POST['tablename'] . " WHERE ID = '" . $_POST['ID']. "';";
						break;
					}
					$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
				};
				
				/* show tables */
				$result = mysql_query('SHOW TABLES');
				while($tableName = mysql_fetch_row($result)) {
				
					$table = $tableName[0];
					if ($table!="measurement_values"){
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
									echo '<td id="' .$table . '_' . $rows . "_" . $i . '">';
									if (substr($fields[$i],-2)=="ID" and strlen($fields[$i])>2){
											$resultID = mysql_query('SELECT * FROM ' . lcfirst($fields[$i])) or die('cannot show columns from '.lcfirst($fields[$i]));
											while($rowID = mysql_fetch_row($resultID)) {
												if ($rowID[0]==$row3[$i]) { 
													echo $rowID[1];
												}
											}
										} else {
											echo $row3[$i];
										}
									echo '</td>';
								}
								echo '<td id="modify_' . $table . '_' . $rows . '" class="modify" onmouseup="clickButton(this)">modify</td>
								<td id="delete_' . $table . '_' . $rows . '" class="delete" onmouseup="clickButton(this)">delete</td></tr>';
							}
							echo '</table><br />';
						}
					}
				}
			?>
		</div>
		<!-- main page ends here -->	
		<footer>
			<hr>
			<p>
				<?php include 'includes/footer.php'; ?>
			</p>
		</footer>
	</body>
</html>
<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		}
	} 
?>