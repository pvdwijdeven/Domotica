<?php 
	$title="Domo import to MySQL";
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
			
				//-->
		</script>
		<?php include "includes/header.php"; ?>
		<!-- main page starts here -->	
		<?php
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
		?>
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