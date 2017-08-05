<?php 
	include "./includes/FieldDispatchDataBase.php";
	include "./includes/decode.php";

	//確認user使用裝置更新資料表 通知只發送當前使用的裝置 「待實作」

	/*
	Test Command: 

	curl https://masqurin.000webhostapp.com/FieldDispatch/updateDaviceToken.php -v 
	--header 'content-type:application/json' -X POST 
	--data 'FieldDispatch={
	"memberId":"1",
	"deviceToken":"a1671413bd7d904842754fe2ed571d1b72f97973ffbef3802f452adccdcfe4a8",
	"deviceType":"1"
	}'

	*/
	$memberId = $inputArray['memberId'];
	$deviceType = $inputArray['deviceType'];	//0 null 1 iOS 2 Android
    $deviceToken = $inputArray['deviceToken'];

	if($inputJson=="") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_INPUT"}';
	}elseif ($memberId ==""){
		$rtn = '{"result" : false,"errorCode":"ERR_NO_MemberId"}';
	}elseif ($deviceToken == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_DeviceToken"}';
	}elseif ($deviceType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_deviceType"}';
	}else {
		$table = 'MemberDeviceToken';
		// $deviceType = '1';	1.iOS	2.Android	3.other...
		$dbcolumns = '`memberId`,`deviceType`,`deviceToken`';
		$dbValus = "$memberId,$deviceType,'$deviceToken'";
		//注意 values非數字的變數都要加'' EX '$deviceToken'
		$arr = $data -> insertAndReportId($table,$dbcolumns,$dbValus);
		$deviceTokenId = $arr['LAST_INSERT_ID()'];
		$rtn = '{"result" : true, "deviceTokenId" : '.$deviceTokenId.'}';
	}
	echo $rtn;
 ?>













 