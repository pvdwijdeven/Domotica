
<?php

include_once 'includes/db_connect.php';
$api_url = 'http://192.168.1.64:8000/api/app/com.internet/getCurrent';
$sql = "SELECT * FROM config where row= 'config'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();


$bearer = $row['bearer'];

$context = stream_context_create(array(
    'http' => array(
        'header' => "Authorization:bearer " . $bearer,
    ),
));
	$result = file_get_contents($api_url, false, $context);
	if ($result===FALSE)
	{echo "Unauthorized request";}
	else{echo $result;};
?>
