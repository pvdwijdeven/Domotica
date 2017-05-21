<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
if (login_check($mysqli) == true) :
$sql = "SELECT * FROM config where row= 'config'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();


$adminName = $row['admin'];

$result = mysql_query('SELECT * FROM measurements');

$i=-1;
while($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
	$i+=1;
	$IDs[$i] = $row['ID'];
	$Floors[$i] = $row['FloorID'];
	$Rooms[$i] = $row['RoomID'];
	$Types[$i] = $row['Type'];
	$Descriptions[$i] = $row['LongDescription'];
	$Graphs[$i] = $row['GraphID'];
	$Interactions[$i] = $row['InteractionID'];
	$UoMs[$i] = $row['Unit'];
	$ValueNumbers[$i] = $row['ValueNumber'];
}

for ($x=0;$x<=$i;$x++){
	$sqltext="SELECT Floor from floorID WHERE ID=" . $Floors[$x];
	$result = mysql_query($sqltext);
	$row = mysql_fetch_row($result);
	$Floors[$x]=$row[0];

	$sqltext="SELECT Room from roomID WHERE ID=" . $Rooms[$x];
	$result = mysql_query($sqltext);
	$row = mysql_fetch_row($result);
	$Rooms[$x]=$row[0];
	
	$sqltext="SELECT Graph from graphID WHERE ID=" . $Graphs[$x];
	$result = mysql_query($sqltext);
	$row = mysql_fetch_row($result);
	$Graphs[$x]=$row[0];
	
	$sqltext="SELECT Interaction from interactionID WHERE ID=" . $Interactions[$x];
	$result = mysql_query($sqltext);
	$row = mysql_fetch_row($result);
	$Interactions[$x]=$row[0];
}
	$IDs = implode ( "," , $IDs );
	$Floors = "'" . implode ( "','" , $Floors ) . "'";
	$Rooms = "'" . implode ( "','" , $Rooms ) . "'";
	$Types = "'" . implode ( "','" , $Types ) . "'";
	$Descriptions = "'" . implode ( "','" , $Descriptions ) . "'";
	$Graphs = "'" . implode ( "','" , $Graphs ) . "'";
	$Interactions = "'" . implode ( "','" , $Interactions ) . "'";
	$UoMs = "'" . implode ( "','" , $UoMs ) . "'";
	$ValueNumbers = implode ( "," , $ValueNumbers );
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
			<!--
			
		function getValues(){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var currentresult = this.responseText;
					currentresult = currentresult.split(',');
					document.getElementById("update").innerHTML="Last update: " + currentresult[0];
					var IDs= "<?php echo $IDs;?>";
					var valuepos = "<?php echo $ValueNumbers;?>";
					IDs=IDs.split(',');
					valuepos=valuepos.split(',');
					var i=0;
					for (i=0;i<IDs.length;i++){
						thisButton = $('#'+ String(IDs[i]));
						var temp = $(thisButton.find('.value'));
						$(temp).html(currentresult[valuepos[i]] + ' ' + generalInfo['UoM'][i]);
					}
				}
			};
			xmlhttp.open("GET", "getCurrentValues.php", true);
			xmlhttp.send();
		}
		
		$( document ).ready(function()  {
			getValues();
			setInterval(getValues, 4000);
		});
			
			var generalInfo = {};
			
			function pressButton(elem) {
				$(elem).attr('class', 'buttonpressed');
			}
			
			function clickButton(elem) {
				releaseButton(elem);
			}

			function releaseButton(elem) {
				$(elem).attr('class', 'button');
			}
			
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
			}

			function releaseTButton(elem) {
				$(elem).attr('class', 'tablebutton');
			}

			function getGeneralInfo(){
				generalInfo['ID'] = [<?php echo $IDs;?>];
				generalInfo['Floor'] = [<?php echo $Floors;?>];
				generalInfo['Room'] = [<?php echo $Rooms;?>];
				generalInfo['Type'] = [<?php echo $Types;?>];
				generalInfo['Description'] = [<?php echo $Descriptions;?>];
				generalInfo['Graph'] = [<?php echo $Graphs;?>];
				generalInfo['Interaction'] = [<?php echo $Interactions;?>];
				generalInfo['UoM'] = [<?php echo $UoMs;?>];
			}
			
			function disableButton(temp){
				temp.attr('class', 'tablebuttondisabled');
				temp.attr('onmousedown', '');
				temp.attr('onmouseleave', '');
				temp.attr('onmouseup', '');
				temp.attr('ontouchstart', '');
				temp.attr('ontouchend', '');
			}
			
			
			function setButtons(){
				for (i=0;i<=generalInfo['ID'].length-1;i++){
					thisButton = addButton(generalInfo['ID'][i]);
					var temp = $(thisButton.find('.location'));
					$(temp).html(generalInfo['Floor'][i] + ' - ' + generalInfo['Room'][i]);
					var temp = $(thisButton.find('.measurement'));
					$(temp).html(generalInfo['Description'][i]);
					var temp = $(thisButton.find('.value'));
					$(temp).html('xx' + ' ' + generalInfo['UoM'][i]);
					var temp = $(thisButton.find('#graphButton'));
					if (generalInfo['Graph'][i]=="no") {
						disableButton(temp);
					}
					var temp = $(thisButton.find('#interactButton'));
					if (generalInfo['Interaction'][i]=="none") {
						disableButton(temp);
						temp.html(' - ');
					} else {
						temp.html(generalInfo['Interaction'][i]);
					}
				}

				$("#0").remove();
			}
			
			$(function() {
				getGeneralInfo();
				setButtons();
				
			});
			
			//-->
		</script>
	</head>
	<body>
		<header>
			<div id=headercontainer>
			<div id="loginfo"><?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b> (<a href="includes/logout.php">Log out</a>)</div>
			<div id="domoheader">Homey Domotica</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<p id="update">Last update: -</p>
		<div class="buttonarea">
			<div class="button" id="0">
				<table>
					<tr>
						<td colspan=3>
							<div class="location">
								Woonkamer
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="measurement">
								Temperatuur
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="value">
								20 &deg;C
							</div>
						</td>
					</tr>
					<tr>
						<td> <div class="tablebutton" id="interactButton" ontouchstart="pressTButton(this)" onmousedown="pressTButton(this)" ontouchend="clickTButton(this)" onmouseup="clickTButton(this)" onmouseleave="releaseTButton(this)">Interact</div></td>
						<td class="splitter"></td>
						<td> <div class="tablebutton" id="graphButton" ontouchstart="pressTButton(this)" onmousedown="pressTButton(this)" ontouchend="clickTButton(this)" onmouseup="clickTButton(this)" onmouseleave="releaseTButton(this)">Graph</div></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="content">
		</div>
		<footer>
			<hr>
			<p>Domo footer stuff here</p>
		</footer>
		
	
	</body>
</html>

<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
