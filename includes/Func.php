<?php 
	class Func {

		function __construct() {}

		function checkObjectInArray ($array,$object) {
			$check = false;
			for ($i = 0; $i < count($array); $i++) {
				if ($object == $array[$i][0]) {
					$check = true;
				}
			}

			return $check;
		}

		function getMissioninfo ($data,$missionIdList) {

			//回傳資料
			$missionInfo = array();
			if ($missionIdList["result"] == true) {
				$missionIdList = $missionIdList["data"];
		
				for ($i = 0; $i < count($missionIdList); $i++) {

					//任務暫存
					$mission = array();
					//任務id
					$mission['missionId'] = $missionIdList[$i][0];
					//任務群組
					$mission['groupId'] = $data -> get('`groupId`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務建立者
					$mission['creatMemberId'] = $data -> get('`creatMemberId`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//執行者id
					$mission['executorId'] = $data -> get('`executorId`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務名稱
					$mission['missionName'] = $data -> get('`missionName`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//聯絡人
					$mission['contactPerson'] = $data -> get('`contactPerson`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//聯絡人電話
					$mission['contactPersonTel'] = $data -> get('`contactPersonTel`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務接受者任務接受位置
					$mission['bookingAddress'] = $data -> get('`bookingAddress`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務接受者任務接受位置
					$mission['bookingLat'] = $data -> get('`bookingLat`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務接受者任務接受位置
					$mission['bookingLon'] = $data -> get('`bookingLon`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務其他資訊
					$mission['missionMemo'] = $data -> get('`missionMemo`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務建立時間
					$mission['createTime'] = $data -> get('`createTime`','`Mission`','`id` = '.$missionIdList[$i][0]);
					//任務工作點資訊z
					$mission["workPointInfo"] = $this -> getMissionInfoFromMissionId($missionIdList[$i][0],$data);
					
					$missionInfo[$i] = $mission;
				}
			}
			return $missionInfo;
		}

		function currentTravel ($id,$startTime,$endTime,$data) {
			$currentLatList = $data -> getListNoStatus('`lat`','`MemberActiveArea`','`memberId` = '.$id." and `statusTime` between '".$startTime."' and '".$endTime."'");
			$currentLonList = $data -> getListNoStatus('`lon`','`MemberActiveArea`','`memberId` = '.$id." and `statusTime` between '".$startTime."' and '".$endTime."'");
			$currentTimeList = $data -> getListNoStatus('`statusTime`','`MemberActiveArea`','`memberId` = '.$id." and `statusTime` > '".$startTime."' and `statusTime` < '".$endTime."'");
			$currentList = array();
			foreach ($currentLatList as $key => $value) {
				$current = array();
				$current['lat'] = $value[0];
				$current['lon'] = $currentLonList[$key][0];
				$current['time'] = $currentTimeList[$key][0];
				$currentList[$key] = $current;
			}
			return $currentList;
		}

		function getMissionInfoFromMissionId ($missionId,$data) {

			$missionInfo = array();
			//用任務編號帶出任務工作點編號清單
			$missionWorkPointIdList = $data -> selcetListNoStatus('`id`','`MissionWorkPoint`',"`missionId` = ".$missionId);
			if ($missionWorkPointIdList["result"] == true) {
				$missionWorkPointIdList = $missionWorkPointIdList["data"];

				$workpointList = array();
				for ($i = 0; $i < count($missionWorkPointIdList); $i++) {
					$missionWorkPointInfo = array();

					//執行者
					$scapegoat = ($data -> selcetListNoStatus('`executorId`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_executorId";
					}
					$missionWorkPointInfo['executorId'] = $scapegoat[0][0];

					//預計抵達時間
					$scapegoat = ($data -> selcetListNoStatus('`expectedArrivalTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					}else {
						$scapegoat = "NO_expectedArrivalTime";
					}
					$missionWorkPointInfo['expectedArrivalTime'] = $scapegoat[0][0];

					//出發時間
					$scapegoat = ($data -> selcetListNoStatus('`departureTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					}else {
						$scapegoat = "NO_departureTime";
					}
					$missionWorkPointInfo['departureTime'] = $scapegoat[0][0];

					//抵達時間
					$scapegoat = ($data -> selcetListNoStatus('`arrivalTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_arrivalTime";
					}
					$missionWorkPointInfo['arrivalTime'] = $scapegoat[0][0];

					//完成時間
					$scapegoat = ($data -> selcetListNoStatus('`completeTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] = true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_completeTime";
					}
					$missionWorkPointInfo['completeTime'] = $scapegoat[0][0];

					$before = array();
					$work = array();
					if ($missionWorkPointInfo['departureTime'] == "0000-00-00 00:00:00") {
						$before['result'] = false;
						$before['errorCode'] = "WP_notDeparture";
						$work['result'] = false;
						$work['errorCode'] = "WP_notStart";
					} else {
						$before['result'] = true;
						$second = $missionWorkPointInfo['arrivalTime'];
						if ($missionWorkPointInfo['arrivalTime'] == "0000-00-00 00:00:00" ) {
							$second = date("Y-m-d H:i:s");
						}
						$current = $this -> currentTravel($missionWorkPointInfo['executorId'],$missionWorkPointInfo['departureTime'],$second,$data); 
						$before['current'] = $current;

						if ($missionWorkPointInfo['arrivalTime'] != "0000-00-00 00:00:00" ) {
							$work['result'] = true;
							$second = $missionWorkPointInfo['completeTime'];
							if ($missionWorkPointInfo['completeTime'] == "0000-00-00 00:00:00") {
								$second = date("Y-m-d H:i:s");
							} 						
							$current = $this -> currentTravel($missionWorkPointInfo['executorId'],$missionWorkPointInfo['arrivalTime'],$second,$data); 
							$work['current'] = $current;
						}
					}

					$missionWorkPointInfo['before'] = $before;
					$missionWorkPointInfo['work'] = $work;

					//工作點地址
					$missionWorkPointInfo['address'] = $data -> get('`address`','`MissionWorkPoint`','`id` = '.$missionWorkPointIdList[$i][0]);

					//工作點順序
					$scapegoat = ($data -> selcetListNoStatus('`order`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_workPointOrder";
					}
					$missionWorkPointInfo['order'] = $scapegoat[0][0];

					//工作點緯度
					$scapegoat = ($data -> selcetListNoStatus('`lat`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_lat";
					}
					$missionWorkPointInfo['lat'] = $scapegoat[0][0];

					//工作點經度
					$scapegoat = ($data -> selcetListNoStatus('`lon`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["data"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "lon";
					}
					$missionWorkPointInfo['lon'] = $scapegoat[0][0];

					//預計抵達時間
					$scapegoat = ($data -> selcetListNoStatus('`expectedArrivalTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					}else {
						$scapegoat = "NO_expectedArrivalTime";
					}
					$missionWorkPointInfo['expectedArrivalTime'] = $scapegoat[0][0];

					//出發時間
					$scapegoat = ($data -> selcetListNoStatus('`departureTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					}else {
						$scapegoat = "NO_departureTime";
					}
					$missionWorkPointInfo['departureTime'] = $scapegoat[0][0];

					//抵達時間
					$scapegoat = ($data -> selcetListNoStatus('`arrivalTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_arrivalTime";
					}
					$missionWorkPointInfo['arrivalTime'] = $scapegoat[0][0];

					//完成時間
					$scapegoat = ($data -> selcetListNoStatus('`completeTime`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_completeTime";
					}
					$missionWorkPointInfo['completeTime'] = $scapegoat[0][0];

					//工作點狀態 完成 未完成...
					$scapegoat = ($data -> selcetListNoStatus('`status`','`MissionWorkPoint`',"`id` = ".$missionWorkPointIdList[$i][0]));
					if ($scapegoat["result"] == true) {
						$scapegoat = $scapegoat["data"];
					} else {
						$scapegoat = "NO_status";
					}
					$missionWorkPointInfo['status'] = $scapegoat[0][0];

					$workpointList[$i] = $missionWorkPointInfo;
				}
				$missionInfo["result"] = true;	
				$missionInfo = $workpointList;
			} else {
				$missionInfo = "NO_missionWorkPoint";
			}
			return $missionInfo;
		}


	}
	$func = new Func();
 ?>