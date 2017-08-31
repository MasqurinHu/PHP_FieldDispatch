<?php 
	include "./includes/FieldDispatchDataBase.php";
	include "./includes/decode.php";

	//確認user使用裝置更新資料表 通知只發送當前使用的裝置 「待實作」

	/*

	Test Command: 

	curl https://masqurin.000webhostapp.com/FieldDispatch/createAccount.php -v --header 'content-type:application/json' -X POST 
	--data 'FieldDispatch={
	"memberAccount":"TesterAccount",
	"memberId":"1",
	"memberSingInType":"Facebook",
	"deviceToken":"a1671413bd7d904842754fe2ed571d1b72f97973ffbef3802f452adccdcfe4a8",
	"deviceType":"1",
	"nickName":"Masqurin",
	"password":"abcdefg,
	"mail":"ABC@google.com",
	"photo":"url://photo",
	}'

	*/

    //web測試給假資料
    // $memberAccount = 'FieldDispatch';
    // $inputJson = 'a';
    // $photo = 'https://masqurin.000webhostapp.com/FieldDispatch/pen.jpg';
    // $password = 'test';
    // $nickName = 'testMember';
    // $memberSingInType = '3';
    // $deviceToken = 'deviceToken';
    // $memberMail = 'test@icloud.com';
    // $deviceType = '2';
    //web測試給假資料^^

    //檢查帳號「fb google fd」是否登入過 資料庫有帳號資料 就找出原本的memberID
    if ($memberAccount != null) {
    	//變數純數字OK 有文字就爆炸
    	//$where = "`memberAccount`=.$memberAccount";
    	$where = "`memberAccount`='$memberAccount'";
    	$checkAccount = $data -> selectLastAdd('`memberId`','`MemberAccount`',$where);

    	
    	if ($checkAccount != null) {
    		$memberId = $checkAccount;
    		$checkAccount = true;
    	}
    }


    //測試sql撈資料是否正常
    // if ($chechkAccount == null) {
    // 	$rtn = '{"result" : false,"errorCode":'.$memberAccount.'}';
    		
    // }else {
    // 	$rtn = '{"result" : true,"errorCode":'.$chechkAccount.'}';
    // }
    //^^^^^^
    
    //web測試
    // $where = "`memberAccount`=".$memberAccount;
    // echo $where."<br>";
    // $memberId = $data -> selectLastAdd('`memberId`','`MemberAccount`',$where);
	// $rtn = '{"result" : true,"errorCode":'.$memberId.'}';




	
	//web測試

	if($inputJson=="") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_INPUT"}';
	} elseif ($photo == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_photo"}';
	} elseif ($password == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_password"}';
	} elseif ($nickName == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_nickName"}';
	} elseif ($memberSingInType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_memberSingInType"}';
	} elseif ($memberAccount == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_memberAccount"}';
	} elseif ($deviceToken == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_DeviceToken"}';
	} elseif ($memberMail == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_memberMail"}';
	} elseif ($deviceType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_deviceType"}';
	} elseif ($checkAccount == true) {

		$table = 'MemberDeviceToken';
		$data -> update($table,'`status`','0','`status`',"1 and `memberId` = $memberId");
		$dbcolumns = '`memberId`,`deviceType`,`deviceToken`';
		// $deviceType = '1';	1.iOS	2.Android	3.other...
		// $deviceToken = "'aaa-bbb-ccc'";

		$dbValus = "$memberId,$deviceType,'$deviceToken'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$deviceTokenId = $arr['LAST_INSERT_ID()'];

		$where = "`memberId`='$memberId'";
    	$nickName = $data -> selectLastAdd('`nickName`','`MemberNickname`',$where);
    	if ($nickName == null) {
    		$nickName = "none";
    	}

    	$memberType = $data -> selectLastAdd('`memberType`','`MemberType`',$where);
    	if ($memberType == null) {
    		$memberType = "none";
    	}

    	$photo = $data -> selectLastAdd('`photo`','`MemberPhoto`',$where);
    	if ($photo == null) {
    		$photo = "none";
    	}

    	$memberMail = $data -> selectLastAdd('`memberMail`','`MemberMail`',$where);
    	if ($memberMail == null) {
    		$memberMail = "none";
    	}

    	$memberTel = $data -> selectLastAdd('`memberTel`','`MemberTel`',$where);
    	if ($memberTel == null) {
    		$memberTel = "none";
    	}
    	include "./includes/PeopleInformation.php";
    	// print_r($peopleInfo);


    	include "./includes/memberInfo.php";

		$rtn = '{ "result" : "'.$checkAccount.'" ,
		"memberId" : "'.$memberId.'" ,
		"deviceToken" : "'.$deviceTokenId.'" ,
		"memberType" : "'.$memberType.'" ,
		"nickName" : "'.$nickName.'" ,
		"photo" : "'.$photo.'" ,
		"memberTel" : "'.$memberTel.'" ,
		"memberMail" : "'.$memberMail.'" ,
		"memberInfo" : '.$memberInfo.' ,
		"peopleInfo" : '.$peopleInfo.' }';
	} else {


		if ($memberId == "0" || $memberId == "") {

			$table = '`MembershipList`';
			$arr = $data -> insertAndReportId($table);
			$memberId = $arr['LAST_INSERT_ID()'];

		}

		$table = 'MemberDeviceToken';
		$dbcolumns = '`memberId`,`deviceType`,`deviceToken`';
		// $deviceType = '1';	1.iOS	2.Android	3.other...
		// $deviceToken = "'aaa-bbb-ccc'";
		$dbValus = "$memberId,$deviceType,'$deviceToken'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$deviceTokenId = $arr['LAST_INSERT_ID()'];

		//type 0 null 1 未綁定 2 綁定登入fb.google.自定... 3 vip 4 課長 5 土豪 6石油王....
		$table = 'MemberType';
		$dbcolumns = '`memberId`,`memberType`';
		$type = '2';
		$dbValus = "$memberId,$type";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberType = $arr['LAST_INSERT_ID()'];
		$memberType = $data -> getList('`memberType`','MemberType','`id` = '.$MemberType)[0][0];

		$table = 'MemberAccount';
		$dbcolumns = '`memberId`,`memberSingInType`,`memberAccount`';
		// $memberSingInType = '1';	1.Facebook	2.Google	3.FieldDispatch...
		// $memberAccount = "'Field1234'";
		$dbValus = "$memberId,$memberSingInType,'$memberAccount'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberAccount = $arr['LAST_INSERT_ID()'];

		$table = 'MemberNickname';
		$dbcolumns = '`memberId`,`nickName`';
		// $nickName = 'Masqurin';
		$dbValus = "$memberId,'$nickName'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberNickname = $arr['LAST_INSERT_ID()'];

		$table = 'MemberPassword';
		$dbcolumns = '`memberId`,`password`';
		// $password = "'Field1234'";
		$dbValus = "$memberId,'$password'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberPassword = $arr['LAST_INSERT_ID()'];

		$table = 'MemberPhoto';
		$dbcolumns = '`memberId`,`photo`';
		// $photo = "'https://scontent.xx.fbcdn.net/v/t1.0-1/p50x50/12243402_10205108656936360_1836205411940366196_n.jpg?oh=a675890d3d03fb19e2f60e1182f063a7&oe=59FA7A15'";
		$dbValus = "$memberId,'$photo'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberPhoto = $arr['LAST_INSERT_ID()'];

		$table = 'MemberMail';
		$dbcolumns = '`memberId`,`memberMail`';
		// $nickName = 'Masqurin';
		$dbValus = "$memberId,'$memberMail'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$MemberMail = $arr['LAST_INSERT_ID()'];

		$table = 'GroupMember';
		$dbcolumns = '`memberId`,`groupListId`';
		// $nickName = 'Masqurin';
		$dbValus = "$memberId,'1'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$groupListId = $arr['LAST_INSERT_ID()'];

		$table = 'GroupAuthority';
		$dbcolumns = '`memberId`,`authority`,`groupId`';
		// $nickName = 'Masqurin';
		$dbValus = "$memberId,'1','1'";
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$GroupAuthority = $arr['LAST_INSERT_ID()'];

		$result = 'true';
		if ($memberId != "") {
			
		} elseif ($deviceTokenId != "") {
			
		} elseif ($MemberType != "") {
			
		} elseif ($MemberAccount != "") {
			
		} elseif ($MemberNickname != "") {
			
		} elseif ($MemberPassword != "") {
			
		} elseif ($MemberMail != "") {
			
		} elseif ($MemberPhoto != "") {
			
		} elseif ($groupListId != "") {
			
		} elseif ($GroupAuthority != "") {
			
		} else {
			$result = 'false';
		}

		include "./includes/PeopleInformation.php";

    	include "./includes/memberInfo.php";

		$rtn = '{"result" : "'.$result.'" , 
		"memberId" : "'.$memberId.'" ,
		"deviceTokenId" : "'.$deviceTokenId.'" ,
		"MemberAccountId" : "'.$MemberAccount.'" ,
		"MemberNicknameId" : "'.$MemberNickname.'" ,
		"MemberPasswordId" : "'.$MemberPassword.'" ,
		"MemberPhotoId" : "'.$MemberPhoto.'" , 
		"MemberMail" : "'.$MemberMail.'" ,
		"memberTypeId" : "'.$MemberType.'" ,
		"memberType" : "'.$memberType.'" ,
		"joinGroupOrder" : "'.$groupListId.'" ,
		"GroupAuthorityOrder" : "'.$GroupAuthority.'" ,
		"memberInfo" : '.$memberInfo.' ,
		"peopleInfo" : '.$peopleInfo.' }';
		
	}
	echo $rtn ;














 ?>