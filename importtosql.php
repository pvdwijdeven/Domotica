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
		<title>Domo tablesview</title>
		<meta name="description" content="Domo administrator - view tables">
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
			<div id="domoheader">Homey Domotica - administrator - insights to mysql</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>

		
		
<?php if ($checkadmin == true and $_SESSION['username'] == $adminName) {

if (!empty($_GET['csv'])) {

	if (($handle = fopen("csv/".$_GET['csv'].".csv", "r")) !== FALSE) {
		$fields="ID, val".$_GET['csv'];
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$epoch=strtotime($data[0]);
			if (intval($epoch)>100000){
				$val=$data[1];
				if ($data[1]=="true"){
					$val=1;
				}
				if ($data[1]=="false"){
					$val=0;
				}
			
			$sqlstring="INSERT INTO measurement_values (" . $fields . ") VALUES (" . $epoch . ", ". $val . ") ON DUPLICATE KEY UPDATE val".$_GET['csv']."=".$val; 
			$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
			}
		}
		fclose($handle);
		echo 'done uploading csv/'.$_GET['csv'].".csv";
	}
} else {
	echo "no file given, post with importtosql.php?csv=NUMBER";
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



