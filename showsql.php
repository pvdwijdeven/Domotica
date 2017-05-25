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
		<title>Domo sql view</title>
		<meta name="description" content="Domo administrator - show sql">
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
			<div id="domoheader">Homey Domotica - administrator - show mysql</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>

		
		
<?php if ($checkadmin == true and $_SESSION['username'] == $adminName) {
	$count=1;
if (!empty($_GET['every'])){
$count=$_GET['every'];}
if (!empty($_GET['csv'])) {

$sqlstring="set @row:=-1";
$sqlstring2="SELECT measurement_values.* FROM measurement_values INNER JOIN (SELECT ID FROM (SELECT @row:=@row+1 AS rownum, ID FROM ( SELECT ID,val".$_GET['csv']." from measurement_values WHERE val".$_GET['csv']." IS NOT NULL ORDER BY ID ) AS sorted ) as ranked WHERE rownum %" . $count." = 0 ) AS subset ON subset.ID = measurement_values.ID";

/*	$sqlstring="SELECT ID,val".$_GET['csv']." from measurement_values WHERE val".$_GET['csv']." IS NOT NULL ORDER BY ID"; */
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$result = mysql_query($sqlstring2) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	while($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
		echo $row['ID']. " : ". date('r', $row['ID']) . " : " . $row['val'.$_GET['csv']];
		echo "<BR>";
	}
} else {
	echo "no value given, post with importtosql.php?csv=NUMBER";
}

 } else { ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/includes/logout.php">logout</a> and/or <a href="index.php">login</a> as user with admin rights.
            </p>
<?php } ?>

		<footer>
			<hr>
			<p>
			<?php include 'includes/footer.php' ?>
			</p>
		</footer>
		
	
	</body>
</html>



