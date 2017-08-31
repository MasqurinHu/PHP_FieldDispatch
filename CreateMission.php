<?php 
include "./includes/decode.php";
if (!$signIn) {	
	include "./includes/SignInCheck.php";
}

if (!$createMission) {
	echo '{"result" : false , "errorCode" : "CM_NO_MISSION_INFO"}';
	exit();
}

$groupId = $createMission['groupId'];
$createMemberid = $createMission['createMemberid'];
$missionName = $createMission['missionName'];
$missionTel = $createMission['missionTel'];
//執行者出發點 不檢查
$missionAddress = $createMission['missionAddress'];
//不檢查
$missionLat = $createMission['missionLat'];
//不檢查
$missionLon = $createMission['missionLon'];
$missionMemo = $createMission['missionMemo'];
$missionWorkPoint = $createMission['missionWorkPoint'];

if ($groupId == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_GROUP_ID"}';
} elseif ($createMemberid == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_CREATE_MEMBER_ID"}';
} elseif ($missionName == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_MISSION_NAME_ID"}';
} elseif ($missionTel == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_MISSION_TEL_ID"}';
} elseif ($missionMemo == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_MISSION_MEMO_ID"}';
} elseif ($missionWorkPoint == "") {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_MISSION_WORK_POINT_ID"}';
} elseif (count($missionWorkPoint) == 0) {
	$rtn = '{"result" : false, "errorCode" : "CM_NO_MISSION_WORK_POINT_List"}';
} else {

	$missionId = $data -> insertAndReportId('`Mission`',
		"`groupId`,`creatMemberId`,`missionName`,`missionTel`,`missionAddress`,`missionLat`,`missionLon`,`missionMemo`",
		"'$groupId','$createMemberid','$missionName','$missionTel','$missionAddress','$missionLat','$missionLon','$missionMemo'");

	for ($i = 0; $i < count($missionWorkPoint); $i++) {

		$order = $missionWorkPoint[$i]['order'];
		//建立任務點時 執行者未知 等接受才知道 不檢查 
		$executorId = $missionWorkPoint[$i]['executorId'];
		$WPadd = $missionWorkPoint[$i]['WPadd'];
		$WPlat = $missionWorkPoint[$i]['WPlat'];
		$WPlon = $missionWorkPoint[$i]['WPlon'];
		$expectedArrivalTime = $missionWorkPoint[$i]['expectedArrivalTime'];

		// echo '{"aaa" : "'.$expectedArrivalTime.'"}';
		// exit();


		//執行者抵達更新 不檢查
		$arrivalTime = $missionWorkPoint['arrivalTime'];
		//執行者完成更新 不檢查
		$completeTime = $missionWorkPoint['completeTime'];

		if ($order == "") {
			$rtn = '{"result" : false, "errorCode" : "CM_WP='.$i.'_NO_ORDER"}';
			break;
		} elseif ($WPadd == "") {
			$rtn = '{"result" : false, "errorCode" : "CM_WP='.$i.'_NO_ADD"}';
			break;
		} elseif ($WPlat == "") {
			$rtn = '{"result" : false, "errorCode" : "CM_WP='.$i.'_NO_LAT"}';
			break;
		} elseif ($WPlon == "") {
			$rtn = '{"result" : false, "errorCode" : "CM_WP='.$i.'_NO_LON"}';
			break;
		} elseif ($expectedArrivalTime == "") {
			$rtn = '{"result" : false, "errorCode" : "CM_WP='.$i.'_NO_EXPECTED_ARRIVAL_TIME"}';
			break;
		} else {
			$data -> insert('`MissionWorkPoint`',
			'`missionId`,`order`,`address`,`lat`,`lon`,`expectedArrivalTime`',
			"'".$missionId['LAST_INSERT_ID()']."','".$order."','".$WPadd."','".$WPlat."','".$WPlon."','".$expectedArrivalTime."'");

			if (!$missionInfo) {
				include "MissionInfo.php";	
			}

			if (!$peopleInfo) {
				include "./includes/PeopleInformation.php";
			}
		}
	}
	if ($rtn == "") {

		if (!$missionInfo) {
			include "MissionInfo.php";
		}

		$rtn = '{"result" : "True", "Mission" : '.$missionInfo.', "peopleInfo" : '.$peopleInfo.', "missionInfo" : '.$missionInfo.'}';	
	}
}
echo $rtn;






 ?>