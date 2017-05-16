
<?php

include_once 'includes/db_connect.php';
$api_url = 'http://192.168.1.64:8000/api/app/com.internet/getCurrent';

$sql = "SELECT bearer FROM config limit 1";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();


$bearer = $row['bearer'];
$bearer = "totallywrongcode";
$context = stream_context_create(array(
    'http' => array(
        'header' => "bearer:" . $bearer,
    ),
));

$result = file_get_contents($api_url, false, $context);
echo $result;
?>
