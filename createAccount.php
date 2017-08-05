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
	"photo":"url://photo",
	}'

	*/

	$memberAccount = $inputArray['memberAccount'];
	$memberSingInType = $inputArray['memberSingInType'];
	$nickName = $inputArray['nickName'];
	$password = $inputArray['password'];
	$photo = $inputArray['photo'];
	$memberId = $inputArray['memberId'];
	$deviceType = $inputArray['deviceType'];	//0 null 1 iOS 2 Android
    $deviceToken = $inputArray['deviceToken'];

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
	} elseif ($deviceType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_deviceType"}';
	} else {
		if ($memberId == "0") {
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

		$result = 'true';
		if ($memberId != "") {
			
		}elseif ($deviceTokenId != "") {
			
		}elseif ($MemberType != "") {
			
		}elseif ($MemberAccount != "") {
			
		}elseif ($MemberNickname != "") {
			
		}elseif ($MemberPassword != "") {
			
		}elseif ($MemberPhoto != "") {
			
		}else {
			$result = 'false';
		}

		$rtn = '{"result" : '.$result.', 
		"memberId" : '.$memberId.',
		"deviceTokenId" : '.$deviceTokenId.',
		"MemberAccountId" : '.$MemberAccount.',
		"MemberNicknameId" : '.$MemberNickname.',
		"MemberPasswordId" : '.$MemberPassword.',
		"MemberPhotoId" : '.$MemberPhoto.', 
		"memberTypeId" : '.$MemberType.'}';
		
	}
	echo $rtn;














 ?>