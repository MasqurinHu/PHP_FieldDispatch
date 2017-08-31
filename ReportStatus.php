<?php 
include "./includes/decode.php";

if (!$signIn) {	
	include "./includes/SignInCheck.php";
}



$check = $data -> getList("`id`",'`MemberActiveArea`','`memberId` = '.$memberId);
if (count($check) > 0) {

	$data -> getUpdate('`MemberActiveArea`','`status` = 0 ','`status` > 0 and `id` = '.$check[0][0]);
}


$data -> insert("`MemberActiveArea`","`memberId`,`lat`,`lon`,`status`","'".$memberId."','".$lat."','".$lon."','".$memberStatus."'");

if (!$peopleInfo) {
	include "./includes/PeopleInformation.php";
}

if (!$missionInfo) {
	include "MissionInfo.php";
}

echo '{"result" : "true" , "peopleInfo" : '.$peopleInfo.' , "missionInfo" : '.$missionInfo.'}';



 ?>
