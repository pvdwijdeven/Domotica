<?php 
	$dummy=$_SERVER;
	$adminpage=false;
	include_once '../includes/db_connect.php';
	include_once '../includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	$FaceURL= $row['FaceURL'];
	$FaceKey1 = $row['FaceKey1'];

	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detect Face</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">

	var faceIDlist=[];
	var faceID="";
	
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
            data: "{'url':'<?php echo $FaceURL;?>1498599440.jpg'}",
        })
        .done(function(data) {
			console.log(data);
			faceID=data[0].faceId;
			var c=document.getElementById("myCanvas");
			var ctx=c.getContext("2d");
			var img=document.getElementById("myImage");
			ctx.drawImage(img,data[0].faceRectangle.left,data[0].faceRectangle.top,data[0].faceRectangle.width,data[0].faceRectangle.height,0,0,data[0].faceRectangle.width,data[0].faceRectangle.height);
			recognizeFace();
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
			console.log(faceIDlist[data[0].persistedFaceId]);
        })
        .fail(function() {
            alert("error");
        });
    }

	
	
	
</script>

	<button onclick="getFaceList()">Detect Face</button>
	<img id="myImage" src="<?php echo $FaceURL;?>/1498599440.jpg">
	<canvas id="myCanvas" width="480" height="480" style="border:1px solid #d3d3d3;"></canvas>
	
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
