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
	$FaceKey1 = $row['FaceKey1'];

	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Get Facelist</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
	var faceIDlist=[];
    $(function() {
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
				faceIDlist.push({
					key: data.persistedFaces[x].persistedFaceId,
					value: data.persistedFaces[x].userData
				})
			}
			console.log(faceIDlist);
			
        })
        .fail(function() {
            alert("error");
        });
    });
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
