<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	 
	if (login_check($mysqli) == true) {
	    $logged = 'in';
	header('Location: '.'domo_main.php');
	die();
	} else {
	    $logged = 'out';
	}
	?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Domo login page</title>
		<meta name="description" content="Domo login page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/JavaScript" src="js/sha512.js"></script> 
		<script type="text/JavaScript" src="js/forms.js"></script> 
		<script type="text/javascript">
			<!--
			function clickTButton(elem) {
				document.getElementById('id01').style.display='block'
			}
			
			//-->
		</script>
	</head>
	<body>
		<header>
			<div id=headercontainer>
				<div id="domoheader">Homey Domotica</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>
		<div class="buttonarea">
			<div class="button" id="0">
				<table>
					<tr>
						<td colspan=3>
							<div class="location">
								<?php
									if (isset($_GET['error'])) {
									    echo 'Error Logging In!';
									}
									?> 
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="measurement">
								Currently logged out
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="value">
							</div>
						</td>
					</tr>
					<tr>
						<td> <button class="tablebutton" id="interactButton" onmouseup="clickTButton(this)">Login</button></td>
						<td class="splitter"></td>
						<td> </td>
					</tr>
				</table>
			</div>
		</div>
		<!-- The Modal -->
		<div id="id01" class="modal">
			<span onclick="document.getElementById('id01').style.display='none'" 
				class="close" title="Close Modal">&times;</span>
			<!-- Modal Content -->
			<form class="modal-content animate" action="includes/process_login.php" method="post" name="login_form">
				<div class="container">
					<label><b>Email</b></label>
					<input type="text" placeholder="Enter Email" name="email" required>
					<label><b>Password</b></label>
					<input type="password" placeholder="Enter Password" name="password" id="password" required>
					<button class="loginbtn" type="submit" onclick="formhash(this.form, this.form.password);" />Login</button>
				</div>
				<div class="container" style="background-color:#f1f1f1">
					<button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
				</div>
			</form>
		</div>
		</td></tr></table></div>
		</div>
		<footer>
			<hr>
			<p>
				<?php include 'includes/footer.php' ?>
			</p>
		</footer>
	</body>
</html>