<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
$sql = "SELECT * FROM config where row= 'config'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$adminName = $row['admin'];
if (login_check($mysqli) == true and $_SESSION['username'] == $adminName) :

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
	</head>
	<body>
		<header>
			<table class="headertable">
			<tr class="headertr">
			<td class="headercenter"><div class="header">Homey Domotica</div>
			<td class="headerleft"><div class="loginfo"> logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b> (<a href="includes/logout.php">Log out</a>)</div></td>
			</tr>
			</table>
			<hr>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<p>actual body here</p>
		<footer>
			<hr>
			<p>footer stuff here</p>
		</footer>
		
	
	</body>
</html>

<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login with admin rights</a>.
            </p>
        <?php endif; ?>
