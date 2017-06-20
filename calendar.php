<?php 
	$title="Domo Dashboard";
	$adminpage=false;
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
		<link rel="stylesheet" href="css/pincode.css?v=3.0">
		<link rel="stylesheet" href="css/calendar.css?v=3.0">
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
		
<iframe id="calendar" src="" style="border-width:0" width="800" height="500" frameborder="0" scrolling="no"></iframe>
<div id="pin">
  <div class="dots">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>
  <p>Geef pincode</p>
  <div class="numbers">
    <div class="number">1</div>
    <div class="number">2</div>
    <div class="number">3</div>
    <div class="number">4</div>
    <div class="number">5</div>
    <div class="number">6</div>
    <div class="number">7</div>
    <div class="number">8</div>
    <div class="number">9</div>
  </div>
</div>	
<script>


function getCalendar(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			$("#calendar").attr('src',this.responseText.trim());
		}
	};
	xmlhttp.open("GET", "config.php?config=calendar", true);
	xmlhttp.send();	
}

function checkPin(input){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText.trim()=="wrong"){
				dots = document.getElementsByClassName('dot');
				dots    = Array.from(dots);
				dots.forEach(function (dot) {
					return $(dot).addClass('wrong');
				});
			}else{
				$("#pin").hide();
				$("#calendar").show();
				getCalendar();
			}

		}
	};
	xmlhttp.open("GET", "config.php?calpin="+input, true);
	xmlhttp.send();
}

(function() {
	
	var input 	= '';
	
	var dots    = document.getElementsByClassName('dot'), 
		numbers = document.getElementsByClassName('number');
		dots    = Array.from(dots);
		numbers = Array.from(numbers);
	
	var numbersBox = document.getElementsByClassName('numbers')[0];
	$(numbersBox).on('click', '.number', function(evt) {
		var number = $( this );
		
		number.addClass( 'grow' );
		input += ( number.index()+1 );
		$( dots[input.length-1] ).addClass( 'active' );
		
		if( input.length >= 4 ) {
			
			checkPin(input);
			setTimeout(function() {
				dots.forEach( (dot) => dot.className = 'dot' );
				input = '';
			}, 900);
			setTimeout(function() {
				document.body.className = '';
			}, 1000);
		}
		setTimeout(function() {
			number.className = 'number';
		}, 1000);
	});
})();


</script>

	<!-- main page ends here -->	
		<footer>
			<hr>
			<p>
				<?php include 'includes/footer.php'; ?>
			</p>
		</footer>
	<script>
	if(self!=top){
					$("header").hide();
					$("footer").hide();
				}
	</script>
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

