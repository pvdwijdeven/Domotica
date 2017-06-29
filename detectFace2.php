<?php 
	$dummy=$_SERVER;
	$title="Domo Detecteer gezicht";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	$FaceURLserver= $row['FaceURL'];
	$app_id = $row['Kairos_app_id'];
	$key = $row['Kairos_key'];

	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
		if (!empty($_GET['faceURL'])){
			$faceURL=$FaceURLserver.$_GET['faceURL'];
		}else{
			$faceURL =  $_POST["faceURL"];
		}
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
		<link rel="stylesheet" href="css/face.css?v=3.0">
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

<script type="text/javascript">
	
	 $(function() {
		 detectFace();
	    });
	
	var faceIDlist=[];
	var faceID="";

    function addFace() {
		if ($("#naam").val()==""){
			alert("voer eerst naam in!");
		}else{
			data=JSON.stringify({'image':'<?php echo $faceURL;?>',"gallery_name":"frontdoor","subject_id":$("#naam").val()});
			$.ajax({
				url: "https://api.kairos.com/enroll",
				beforeSend: function(xhrObj){
					// Request headers
					xhrObj.setRequestHeader("Content-Type","application/json");
					xhrObj.setRequestHeader("app_id","<?php echo $app_id; ?>");
					xhrObj.setRequestHeader("app_key","<?php echo $key; ?>");
				},
				type: "POST",
				// Request body
				data: data
			})
			.done(function(data) {
				alert("Persoon toegevoegd!");
				console.log(data);
			})
			.fail(function() {
				alert("error");
			});
		}
    }

	
	function detectFace(){
		data=JSON.stringify({'image':'<?php echo $faceURL;?>',"gallery_name":"frontdoor"});
        $.ajax({
            url: "https://api.kairos.com/recognize",
            beforeSend: function(xhrObj){
                // Request headers
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("app_id","<?php echo $app_id; ?>");
				xhrObj.setRequestHeader("app_key","<?php echo $key; ?>");
            },
            type: "POST",
            // Request body
            data: data
        })
        .done(function(data) {
			console.log(data);
			if (data.hasOwnProperty("Errors")){
				$('#estimates').html("no face detected");
			}else{
				var c=document.getElementById("myCanvas");
				var ctx=c.getContext("2d");
				var img=document.getElementById("myImage");
				if (data.images[0].transaction.message=="No match found"){
					$('#nameguess').html("persoon niet in database?");
					$("#addface").show();
					console.log("persoon niet in database?");
				}else{
				$('#nameguess').html(data.images[0].transaction.subject_id+" ("+parseInt(parseFloat(data.images[0].transaction.confidence)*100)+"%)");
				console.log(data.images[0].transaction.subject_id);
				}
				$('#myCanvas').attr('width',data.images[0].transaction.width+"px");
				$('#myCanvas').attr('height',data.images[0].transaction.height+"px");
				ctx.drawImage(img,data.images[0].transaction.topLeftX,data.images[0].transaction.topLeftY,data.images[0].transaction.width,data.images[0].transaction.height,0,0,data.images[0].transaction.width,data.images[0].transaction.height);

			}
        
		})
        .fail(function() {
            alert("error");
        });
    }
	
	
</script>

	<table id="facetable"><tr>
	<td><img id="myImage" width="200px" src="<?php echo $faceURL;?>"></td>
	<td><canvas id="myCanvas" width="1" height="1" style="border:1px solid #d3d3d3;"></canvas></td><td><div id="nameguess"></div><div id="estimates"></div><div id="addface"><div>Naam:</div><input type="text" id="naam"><button id="b_addface" onclick="addFace()">Toevoegen</button></div></td>
	</tr><tr><td>originele afbeelding</td><td>gezicht</td></tr></table>
	
		<!-- main page ends here -->	
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