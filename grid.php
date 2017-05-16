
<?php
    include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	 
	sec_session_start();
	if (login_check($mysqli) == true) :
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();



?>
	
<!doctype html>
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
      $(function() {
        $(".users").grid();
      });
    </script>

	</head>
	<body>
	<table action="ajax.php">
     	<tr>
     		<th col="Username">Username</th>
     		<th col="FirstName">First Name</th>
     		<th col="Lastname">Last Name</th>
     		<th col="Email">Email</th>
		</tr>
	</table>
	
	<?php
		$adminName = $row['admin'];
	require_once("OpenJSGrid/grid.php");
	$grid = new Grid("users", array(
		"save"=>true,
		"delete"=>true
	),$mysqlmeasi);
	?>
	</body>
	</html>

<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
