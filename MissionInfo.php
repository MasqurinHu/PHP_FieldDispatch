<?php 
	//web測試給假資料
 //    $password = 'aaa';
 //    $memberSingInType = '2';
 //    $deviceToken = 'deviceToken';
 //    $deviceType = '1';
 //    $memberType = '2';
	// $memberAccount = 'Masqurin';
	// $memberId = '1';
    // ︿︿︿︿︿︿︿︿︿︿
//如果沒有生份檢查就檢查身分
    if ($signIn != true) {
		include "./includes/SignInCheck.php";
	}
	// $memberId = 100;
	//任務列表「回傳」
	$missionInfo = array();
	
	//委派任務
	//取得委派任務清單
	$missionIdList = $data -> selcetListNoStatus('`id`','`Mission`','`creatMemberId` = '.$memberId);
	$missionInfo["missionDelegate"] = $func -> getMissioninfo($data,$missionIdList);

	// 取得可接受任務清單 
	$missionIdList = $data -> selcetList('`id`','`Mission`','');
	$missionInfo["missionAcceptableList"] = $func -> getMissioninfo($data,$missionIdList);

	//取得已完成任務清單
	$missionIdList = $data -> selcetListNoStatus('`id`','`Mission`','`status` = -1 and `creatMemberId` = '.$memberId);
	$missionInfo["missionCompleteList"] = $func -> getMissioninfo($data,$missionIdList);

	$missionInfo = json_encode($missionInfo);

	


 ?>