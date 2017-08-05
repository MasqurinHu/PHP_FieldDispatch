<?php 
	include "./includes/FieldDispatchDataBase.php";
	include "./includes/decode.php";
	/*
	Test Command: 

	curl https://masqurin.000webhostapp.com/FieldDispatch/newDevice.php -v 
	--header 'content-type:application/json' -X POST 
	--data 'FieldDispatch={
	"deviceType":"1",
	"deviceToken":"a1671413bd7d904842754fe2ed571d1b72f97973ffbef3802f452adccdcfe4a8",
	}'

	*/
	
	$deviceType = $inputArray['deviceType'];	//0 null 1 iOS 2 Android
    $deviceToken = $inputArray['deviceToken'];

	if($inputJson=="") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_INPUT"}';
	}elseif ($deviceToken == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_DeviceToken"}';
	}elseif ($deviceType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_deviceType"}';
	}else {

		$table = '`MembershipList`';
		// $methord = 'max';
		// $column = '`id`';
	
		// $data -> insert($table);
		
		// $num = $data -> selectFunc($methord,$column,$table);
		$arr = $data -> insertAndReportId($table);
		$num = $arr['LAST_INSERT_ID()'];
		$table = 'MemberDeviceToken';
		$dbcolumns = '`memberId`,`deviceType`,`deviceToken`';
		// $deviceType = '1';	1.iOS	2.Android	3.other...
		// $deviceToken = "'aaa-bbb-ccc'";
		$dbValus = "$num,$deviceType,'$deviceToken'";
		$data -> insert($table,$dbcolumns,$dbValus);

		//type 0 null 1 未綁定 2 綁定登入fb.google.自定... 3 vip 4 課長 5 土豪 6石油王....
		$table = 'MemberType';
		$dbcolumns = '`memberId`,`memberType`';
		$type = '1';
		$dbValus = "$num,$type";
		$data -> insert($table,$dbcolumns,$dbValus);

		$rtn = '{"result" : true, "memberId" : '.$num.', "memberType" : '.$type.'}';
	}
	// $data -> insert($table);
	// $nam = $data -> InsertId();
	// $naa =  $nam['LAST_INSERT_ID()'];
	$naa =  $nam['0'];
	// echo $naa.'<br>';
	foreach ($nam as $key => $value) {
		// echo '我是key'.$key.'<br>'.'我是值'.$value.'<br>';
	}
	// $rtn = '{"result" : true, "memberId" : '.$naa.'}';
	echo $rtn;
 ?>