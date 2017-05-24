
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Domo administrator</title>
		<meta name="description" content="Domo administrator">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
			<!--
			
		
		$( document ).ready(function()  {
			getGeneralInfo();
			setButtons();
		});
			
			var generalInfo = {};
			
		
			function addButton(ID){
				var tempEl = $( "#0" ).clone();
				$(tempEl).attr('id',ID);
				tempEl.appendTo( ".buttonarea" );
				return tempEl;
			}
			
			function showHideMenu(){
				if ($("nav").attr('class')=='menuShown'){
					$("nav").attr('class','menuHidden');
				} else {
					$("nav").attr('class','menuShown');
				}
			}

			function pressTButton(elem) {
				$(elem).attr('class', 'tablebuttonpressed');
			}
			
			function clickTButton(elem) {
				releaseTButton(elem);
				switch ($(elem).attr("id")){
				case "graphButton_1":
					document.location.href = 'view_tables.php';
					break;
				case "graphButton_2":
					document.location.href = 'view_users.php';
					break;
				case "graphButton_3":
					document.location.href = 'index.php';
					break;
				case "graphButton_4":
					document.location.href = 'includes/logout.php';
					break;
				}
			}

			function releaseTButton(elem) {
				$(elem).attr('class', 'tablebutton');
			}

			function getGeneralInfo(){
				//re-using the standard domo tiles here
				generalInfo['ID'] = [1,2,3,4];
				generalInfo['Floor'] = ["","","",""];
				generalInfo['Room'] = ["<br>","<br>","<br>","<br>"];
				generalInfo['Type'] = ["<br>","<br>","<br>","<br>"];
				generalInfo['Description'] = ["Show/modify tables","Show/modify users","Back to main","Logout administrator"];
				generalInfo['Graph'] = ["yes","yes","yes","yes"];
				generalInfo['Interaction'] = ["none","none","none","none"];
				generalInfo['UoM'] = ["<br>","<br>","<br>","<br>"];
			}
			
			function disableButton(temp){
				temp.attr('class', 'tablebuttondisabled');
				temp.attr('onmouseup', '');
				temp.prop("disabled",true);
			}
			
			
			function setButtons(){
				for (i=0;i<=generalInfo['ID'].length-1;i++){
					thisButton = addButton(generalInfo['ID'][i]);
					var temp = $(thisButton.find('.location'));
					$(temp).html(generalInfo['Floor'][i] + '  ' + generalInfo['Room'][i]);
					var temp = $(thisButton.find('.measurement'));
					$(temp).html(generalInfo['Description'][i]);
					var temp = $(thisButton.find('.value'));
					$(temp).html('' + ' ' + generalInfo['UoM'][i]);
					var temp = $(thisButton.find('#graphButton'));
					if (generalInfo['Graph'][i]=="no") {
						disableButton(temp);
					}
					$(temp).attr('id','graphButton_'+generalInfo['ID'][i]);
					var temp = $(thisButton.find('#interactButton'));
					if (generalInfo['Interaction'][i]=="none") {
						disableButton(temp);
						temp.html(' - ');
					} else {
						temp.html(generalInfo['interactButton_'][i]);
					}
					$(temp).attr('id','interactButton_'+generalInfo['ID'][i]);
				}

				$("#0").remove();
			}
			

			
			//-->
		</script>
	</head>
	<body>
		<header>
				<?php
		include_once 'includes/db_connect.php';
		include_once 'includes/functions.php';
		$checked=login_check($mysqli);
		$sql = "SELECT * FROM config where row= 'config'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$adminName = $row['admin'];
		
		?>

			<div id=headercontainer>
			<div id="loginfo"><?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b> (<a href="includes/logout.php">Log out</a>)</div>
				<div id="domoheader">Homey Domotica - administrator page</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>
				<div class="buttonarea">
		<?php
		if ($checked == true and $_SESSION['username'] == $adminName) {

		?>

			<div class="button" id="0">
				<table>
					<tr>
						<td colspan=3>
							<div class="location">
								
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="measurement">
								Show/Modify tables
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
						<td> <button class="tablebutton" id="interactButton" onmouseup="clickTButton(this)">-</button></td>
						<td class="splitter"></td>
						<td> <button class="tablebutton" id="graphButton" onmouseup="clickTButton(this)">Go</button></td>
					</tr>
				</table>
			</div>
		<?php }else { ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="/includes/logout.php">logout</a> and/or <a href="index.php">login</a> as user with admin rights.
            </p>
        <?php } ?>
			</div>

		<footer>
			<hr>
			<p>
			<?php include 'includes/footer.php' ?>
			</p>
		</footer>
		
	
	</body>
</html>


