<?php 
include "./includes/decode.php";
if (!$signIn) {	
	include "./includes/SignInCheck.php";
}

if (!$acceptMission) {
	echo '{"result" : false , "errorCode" : "AM_NO_MISSION_INFO"}';
	exit();
}

$missionId = $acceptMission['missionId'];
$bookingAddress = $acceptMission['bookingAddress'];
$executorId = $acceptMission['executorId'];




if ($missionId == "") {
	echo '{"result" : false , "errorCode" : "AM_NO_MISSION_ID"}';
	exit();
} elseif ($executorId == "") {
	echo '{"result" : false , "errorCode" : "AM_NO_EXECUTOR_ID"}';
	exit();
}

$bookingTime = $data -> get('`bookingTime`','`Mission`',"`id` = '".$missionId."'");
$status = $data -> get('`status`','`Mission`',"`id` = '".$missionId."'");
if ($bookingTime != "0000-00-00 00:00:00") {
	echo '{"result" : false , "errorCode" : "AM_MISSION_HAS_BEEN_ACCEPD"}';
	exit();
} elseif (!$bookingTime) {
	echo '{"result" : false , "errorCode" : "AM_NO_MISSION"}';
	exit();
} elseif ($status != 1) {
	echo '{"result" : false , "errorCode" : "AM_NO_MISSION"}';
	exit();
}
$bookingTime = date("Y-m-d H:i:s");

$arr = array();
$arr['bookingAddress'] = $bookingAddress;
$arr['bookingLat'] = $lat;
$arr['bookingLon'] = $lon;
$arr['bookingTime'] = $bookingTime;
$arr['missionId'] = $missionId;
$arr['executorId'] = $executorId;

$data -> autoCommitClose();

$data -> prepareUpdate('`Mission`',"`executorId` = :executorId , `bookingAddress` = :bookingAddress , `bookingLat` = :bookingLat , `bookingLon` = :bookingLon , `bookingTime` = :bookingTime , `status` = '2' ",$arr,"`id` = :missionId");
$checkBookingTime = $data -> get('`bookingTime`','`Mission`',"`bookingTime` = '".$bookingTime."'");

if ($checkBookingTime != $bookingTime) {
	echo '{"result" : false, "checkBookingTime" : "'.$checkBookingTime.'"}';	
	$data -> rollback();
}
$checkLat = $data -> get('`bookingLat`','`Mission`',"`bookingLat` = '".$lat."'");
// echo '{"result" : false, "bookingLat" : "'.$lat.'"}';
// if ($checkLat != $lat) {
// 	echo '{"result" : false, "bookingLat" : "'.$checkLat.'", "lat" : "'.$lat.'"}';	
// 	$data -> rollback();
// }
$data -> autoCommitOpen();
exit();
echo '{"result" : false, "checkAdd" : "'.$checkBookingTime.'"}';	

exit();


if (!$missionInfo) {
	include "MissionInfo.php";	
}

if (!$peopleInfo) {
	include "./includes/PeopleInformation.php";
}

echo '{"result" : false, "Mission" : '.$missionInfo.', "peopleInfo" : '.$peopleInfo.', "missionInfo" : '.$missionInfo.'}';	
	











 ?>