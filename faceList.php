<?php 
	$dummy=$_SERVER;
	$title="Domo Face List";
	$adminpage=true;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	$FaceKey1 = $row['FaceKey1'];

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
		 getFaceList();
	    });
	
	var faceIDlist=[];
	var faceID="";


	function removeFace() {
		if ($("#deleteid").val()==""){
			alert("vul id in!");
		}else{
			var params = {
				// Request parameters
			};
		  
			$.ajax({
				url: "https://westeurope.api.cognitive.microsoft.com/face/v1.0/facelists/frontdoor/persistedFaces/"+$("#deleteid").val()+"?" + $.param(params),
				beforeSend: function(xhrObj){
					// Request headers
					xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","<?php echo $FaceKey1;?>");
				},
				type: "DELETE",
				// Request body
				data: "{body}",
			})
			.done(function(data) {
				alert("success");
			})
			.fail(function() {
				alert("error");
			});
			getFaceList();
		}
    };

	
    function getFaceList() {
        var params = {
            // Request parameters
        };
      
        $.ajax({
            url: "https://westeurope.api.cognitive.microsoft.com/face/v1.0/facelists/frontdoor?" + $.param(params),
            beforeSend: function(xhrObj){
                // Request headers
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","<?php echo $FaceKey1 ?>");
            },
            type: "GET",
            // Request body
            data: "{body}",
        })
        .done(function(data) {
 			console.log(data);
			var idtext="";
			var members = data.persistedFaces.length;
			for (x=0;x<members;x++){
				faceIDlist[data.persistedFaces[x].persistedFaceId]= data.persistedFaces[x].userData;
				idtext+=data.persistedFaces[x].persistedFaceId+":"+data.persistedFaces[x].userData+"<BR>";
			}
			console.log(faceIDlist);
			$("#list").html(idtext);
			
        })
        .fail(function() {
            alert("error");
        });
    }

</script>
		<div id="list"></div>
		<input type='text' id='deleteid'>
		<button id="b_deleteid" onclick="removeFace()">Verwijder ID</button>
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