<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
$sql = "SELECT * FROM config where row= 'config'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$adminName = $row['admin'];
$checkadmin= login_check($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Domo - What happened?</title>
		<meta name="description" content="Domo - What happened?">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	</head>
	<body>
		<header>
			<div id=headercontainer>
			<div id="loginfo"><?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b> (<a href="includes/logout.php">Log out</a>)</div>
			<div id="domoheader">Domo - What happened?</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>

		
		
<?php 
$meas=array();
$total=-1;
$result=mysql_query('SELECT * FROM measurements') or die('cannot show columns from measurements');
while($row = mysql_fetch_row($result, MYSQL_ASSOC))
	{
		$total+=1;
		$column[$total]='val'.$row['ValueNumber'];
		$meas['val'.$row['ValueNumber']]=$row;
}

$count=1;
if (!empty($_GET['every'])){
$count=$_GET['every'];}

$to=time();
if (!empty($_GET['to'])){
$to=$_GET['to'];}

$from=$to-(24*60*60);
if (!empty($_GET['from'])){
$to=$_GET['from'];}

$sqlstring="set @row:=-1";
$sqlstring2="SELECT measurement_values.* FROM measurement_values INNER JOIN (SELECT ID FROM (SELECT @row:=@row+1 AS rownum, ID FROM ( SELECT ID,val".$_GET['csv']." from measurement_values WHERE ID BETWEEN ".$from." AND ".$to." ORDER BY ID ) AS sorted ) as ranked WHERE rownum %" . $count." = 0 ) AS subset ON subset.ID = measurement_values.ID";

	echo "<table class='log'><tr><th>Datum</th><th>tijd</th><th>Meting</th><th>Waarde</th></tr>";
	
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$result = mysql_query($sqlstring2) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$lastdate = "";
	$lasttime = "";
	while($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
		for ($x=1;$x<=$total;$x++){
			if (array_key_exists($column[$x],$row) AND !is_null($row[$column[$x]])){
				if (date('Y-m-d',$row['ID'])==$lastdate){
					echo "<tr><td></td>";
				}else{
					echo "<tr><td>".date('Y-m-d',$row['ID'])."</td>";
					$lastdate = date('Y-m-d',$row['ID']);
				}
				if (date('H:i:s',$row['ID'])==$lasttime){
					echo "<td></td>";
				}else{
					echo "<td>".date('H:i:s',$row['ID'])."</td>";
					$lasttime = date('H:i:s',$row['ID']);
				}
				echo "<td>".$meas[$column[$x]]['LongDescription']."</td>";
				if ($meas[$column[$x]]['AnalogID']==1){
					echo "<td>".$row[$column[$x]].$meas[$column[$x]]['Unit']."</td></tr>";
				}else{
					if ($row[$column[$x]]==1){
						echo "<td>".$meas[$column[$x]]['TrueText']."</td></tr>";
					}else{
						echo "<td>".$meas[$column[$x]]['FalseText']."</td></tr>";
					}
				}
			}
		}

	}
	echo "</table>"
?>


		<footer>
			<hr>
			<p>
			<?php include 'includes/footer.php' ?>
			</p>
		</footer>
		
	
	</body>
</html>



