<?php 

	if (!$data) {
		include "FieldDispatchDataBase.php";
	}
	if (!$signIn) {
		include "SignInCheck.php";
	}
	
	$peopleInfo = array();
	//測試id
	// $memberId = 1;

	//取得 用memberId查出所在群組
	$guoupList = $data -> getList('`groupListId`','`GroupMember`',"`memberId` = ".$memberId);
	//所有群組成員不重複id 下方用id取出大頭 暱稱 電話 後打包
	$allGroupMemberId = array();
	
	//ecro用 群組所有資訊
	$groupInfo = array();

	//用成員id找出所有加入群組id
	for ($i = 0; $i < count($guoupList); $i++) {
		$groupMemberList = $data -> getList('`memberId`','`GroupMember`',"`groupListId` = ".$guoupList[$i][0]);
		
		//加入群組資料
		$memberGroupList = array();

		$groupMemberIdList = array();
		//找出各群組的成員id
		for ($a = 0; $a < count($groupMemberList); $a ++) {

			$groupMemberInfo = array();
			// 用memberId及groupId尋找成員在群組內的階級
			$groupAuthority = $data -> getList('`authority`','`GroupAuthority`',"`groupId` = ".$guoupList[$i][0]." and `memberId` = ".$groupMemberList[$a][0]);

			$groupMemberInfo['memberId'] = $groupMemberList[$a][0];
			$groupMemberInfo['memberAuthority'] = $groupAuthority[0][0];
			$groupMemberIdList[$a] = $groupMemberInfo;

			//將不同群組的相同成員排除 減少資料量
			$check = true;
			if (count($allGroupMemberId) == 0) {
				$allGroupMemberId[count($allGroupMemberId)] = $groupMemberList[$a][0];
			}else {

				$d = count($allGroupMemberId);
				for ($c = 0; $c < $d; $c ++) {

					if ($groupMemberList[$a][0] == $allGroupMemberId[$c]) {
						$check = false;
					}
					if ($check == true && $c == count($allGroupMemberId) - 1 ) {
						$allGroupMemberId[count($allGroupMemberId)] = $groupMemberList[$a][0];
					}
				}
			}
		}

		// 用groupId尋找groupName
		$groupName = $data -> getList('`groupName`','`GroupList`',"`id` = ".$guoupList[$i][0]);

		$memberGroupList['groupId'] = $guoupList[$i][0];
		$memberGroupList['groupName'] = $groupName[0][0];
		$memberGroupList['groupMemberList'] = $groupMemberIdList;
		$groupInfo[$i] = $memberGroupList;
	}
	//將成員陣列 添加到輸出的大陣列
	$peopleInfo['groupList'] = $groupInfo;

	//如果加入好友功能 這邊加入好友資訊跟群組成員過濾


	//echo用 取得所有成員「不重複」 暱稱 電話 頭像 階級
	$groupMemberInfo = array();
	for ($i = 0; $i < count($allGroupMemberId); $i++) {
		$memberInfo = array();
		$memberInfo['id'] = $allGroupMemberId[$i];
		$nickName = $data -> getList('`nickName`','`MemberNickname`',"`memberId` = ".$allGroupMemberId[$i]);
		$memberInfo['nickName'] = $nickName[0][0];
		$tel = $data -> getList('`memberTel`','`MemberTel`',"`memberId` = ".$allGroupMemberId[$i]);
		$memberInfo['tel'] = $tel[0][0];
		// if (!$memberInfo['tel']) {
		// 	$memberInfo['tel'] = 0;
		// }
		$photo = $data -> getList('`photo`','`MemberPhoto`',"`memberId` = ".$allGroupMemberId[$i]);
		$memberInfo['photo'] = $photo[0][0];
		$photo = $data -> getList('`photo`','`MemberPhoto`',"`memberId` = ".$allGroupMemberId[$i]);

		$memberStatusLat = $data -> getMemberStatus('`lat`','`MemberActiveArea`','`memberId` = '.$allGroupMemberId[$i]);
		$memberInfo['Lat'] = $memberStatusLat;

		$memberStatusLon = $data -> getMemberStatus('`lon`','`MemberActiveArea`','`memberId` = '.$allGroupMemberId[$i]);
		$memberInfo['lon'] = $memberStatusLon;

		$memberStatus = $data -> getMemberStatus('`status`','`MemberActiveArea`','`memberId` = '.$allGroupMemberId[$i]);
		$memberInfo['status'] = $memberStatus;


		// echo '{"memberStatus" : "'.$memberStatusLon.'"}';
		// exit();


		$groupMemberInfo[$i] = $memberInfo;
	}

	$peopleInfo['peopleList'] = $groupMemberInfo;
	// print_r($peopleInfo);
	$peopleInfo = json_encode($peopleInfo,JSON_UNESCAPED_UNICODE);




 ?>