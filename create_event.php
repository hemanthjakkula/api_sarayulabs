<?php

require ('db.php');
$rest_json = file_get_contents("php://input");
$headers = getallheaders();
//echo $headers['authorization'];
if (isset($headers['authorization'])) {
    $token = $headers['authorization'];
    $query_for_userid = "SELECT userid FROM users WHERE token = '$token' ";
    $result = $connect->query($query_for_userid);
    $feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
    //echo($feedData[0]['userid']);
    $logged_in_userid = $feedData[0]['userid'];
$jsonData = json_decode($rest_json, true);

//var_dump($jsonData);
//print_r($jsonData);
$headers = getallheaders();
//echo $headers['authorization'];

if ( isset($jsonData['event_name']) && isset($jsonData['event_date']) && isset($jsonData['event_start_time']) && isset($jsonData['event_end_time'])) {

	$query = "INSERT INTO calender_events( userid,event_name, event_date,event_start_time, event_end_time ) VALUES ('$logged_in_userid','$jsonData[event_name]', '$jsonData[event_date]', '$jsonData[event_start_time]', '$jsonData[event_end_time]') ";
	//echo $query;
	$connect->query($query);

	$query1 = "SELECT event_id, userid,event_name, event_date,event_start_time, event_end_time FROM calender_events WHERE event_id = LAST_INSERT_ID() AND userid='$logged_in_userid'  ";
//echo $query1;
	$result = $connect->query($query1);
 	
 	$feedData = mysqli_fetch_all($result,MYSQLI_ASSOC);
$feedData=json_encode($feedData[0]);


echo ($feedData);
}
}
?>