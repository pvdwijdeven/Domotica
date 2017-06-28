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
	$FaceKey1 = $row['FaceKey1'];

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
		 getFaceList();
	    });
	
	var faceIDlist=[];
	var faceID="";

    function addFace() {
		if ($("#naam").val()==""){
			alert("voer eerst naam in!");
		}else{
			var params = {
				// Request parameters
				"userData": $("#naam").val(),
				//"targetFace": "{string}",
			};
		  
			$.ajax({
				url: "https://westeurope.api.cognitive.microsoft.com/face/v1.0/facelists/frontdoor/persistedFaces?" + $.param(params),
				beforeSend: function(xhrObj){
					// Request headers
					xhrObj.setRequestHeader("Content-Type","application/json");
					xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","<?php echo $FaceKey1 ?>");
				},
				type: "POST",
				// Request body - put photo URL here
				data: "{'url':'<?php echo $faceURL;?>'}",
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
			var members = data.persistedFaces.length;
			for (x=0;x<members;x++){
				faceIDlist[data.persistedFaces[x].persistedFaceId]= data.persistedFaces[x].userData
			}
			console.log(faceIDlist);
			detectFace();
			
        })
        .fail(function() {
            alert("error");
        });
    }

	
	
	function detectFace(){
        var params = {
            // Request parameters
            "returnFaceId": "true",
            "returnFaceLandmarks": "false",
            "returnFaceAttributes": "age,gender,headPose,smile,facialHair,glasses,emotion,hair,makeup,occlusion,accessories",
        };
      
        $.ajax({
            url: "https://westeurope.api.cognitive.microsoft.com/face/v1.0/detect?" + $.param(params),
            beforeSend: function(xhrObj){
                // Request headers
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","<?php echo $FaceKey1 ?>");
            },
            type: "POST",
            // Request body
            data: "{'url':'<?php echo $faceURL;?>'}",
        })
        .done(function(data) {
			console.log(data);
			if (data.length==0){
				$('#estimates').html("no face detected");
			}else{
				faceID=data[0].faceId;
				var c=document.getElementById("myCanvas");
				var ctx=c.getContext("2d");
				var img=document.getElementById("myImage");
				$('#myCanvas').attr('width',data[0].faceRectangle.width+"px");
				$('#myCanvas').attr('height',data[0].faceRectangle.height+"px");
				ctx.drawImage(img,data[0].faceRectangle.left,data[0].faceRectangle.top,data[0].faceRectangle.width,data[0].faceRectangle.height,0,0,data[0].faceRectangle.width,data[0].faceRectangle.height);
				var estimates="<BR>Schattingen:<BR><BR>";
				estimates+="leeftijd: "+data[0].faceAttributes.age+"<BR>";
				estimates+="gender: "+data[0].faceAttributes.gender+"<BR>";
				estimates+="glasses: "+data[0].faceAttributes.glasses+"<BR>";
				estimates+="anger: "+data[0].faceAttributes.emotion.anger+"<BR>";
				estimates+="contempt: "+data[0].faceAttributes.emotion.contempt+"<BR>";
				estimates+="disgust: "+data[0].faceAttributes.emotion.disgust+"<BR>";
				estimates+="fear: "+data[0].faceAttributes.emotion.fear+"<BR>";
				estimates+="happiness: "+data[0].faceAttributes.emotion.happiness+"<BR>";
				estimates+="neutral: "+data[0].faceAttributes.emotion.neutral+"<BR>";
				estimates+="sadness: "+data[0].faceAttributes.emotion.sadness+"<BR>";
				estimates+="surprise: "+data[0].faceAttributes.emotion.surprise+"<BR>";
				$('#estimates').html(estimates);
				recognizeFace();
			}
        })
        .fail(function() {
            alert("error");
        });
    }
	
    function recognizeFace() {
        var params = {
            // Request parameters
        };
      
        $.ajax({
            url: "https://westeurope.api.cognitive.microsoft.com/face/v1.0/findsimilars?" + $.param(params),
            beforeSend: function(xhrObj){
                // Request headers
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key","<?php echo $FaceKey1 ?>");
            },
            type: "POST",
            // Request body
            data: "{'faceId':'"+faceID+"','faceListId':'frontdoor','maxNumOfCandidatesReturned':10,'mode': 'matchPerson'}",
        })
        .done(function(data) {
			console.log(data);
			if (data.length==0){
				$('#nameguess').html("persoon niet in database?");
				$("#addface").show();
				console.log("persoon niet in database?");
			}else{
			$('#nameguess').html(faceIDlist[data[0].persistedFaceId]+" ("+parseInt(parseFloat(data[0].confidence)*100)+"%)");
			console.log(faceIDlist[data[0].persistedFaceId]);
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