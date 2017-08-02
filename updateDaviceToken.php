<?php 
	include "./includes/FieldDispatchDataBase.php";

	/*

	Test Command: 

	curl https://masqurin.000webhostapp.com/newDevice.php -v --header 'content-type:application/json' -X POST 
	--data 'FieldDispatch={
	"nickName":"Masqurin",
	"deviceType":"1",
	"deviceToken":"a1671413bd7d904842754fe2ed571d1b72f97973ffbef3802f452adccdcfe4a8",
	"telNumber":"0987654321",
	}'

	*/

	//$inputJson = urldecode(file_get_contents("php://input"));
	$inputJson = $_POST['FieldDispatch'];

	$inputJson=str_replace('FieldDispatch=', '', $inputJson);

	//		echo "Input:".$inputJson;

		$inputArray=json_decode($inputJson, true);
	
	//		var_dump($inputArray);
	
		$deviceType = $inputArray['deviceType'];	//0 null 1 iOS 2 Android
        $deviceToken = $inputArray['deviceToken'];
        $nickName = $inputArray['nickName'];   
        $telNumber = $inputArray['telNumber']; 



	if($inputJson=="")
	{
		$rtn = '{"result" : false,"errorCode":"ERR_NO_INPUT"}';
	}elseif ($nickName == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_nickName"}';
	}elseif ($deviceToken == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_DeviceToken"}';
	}elseif ($telNumber == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_telNumber"}';
	}elseif ($deviceType == "") {
		$rtn = '{"result" : false,"errorCode":"ERR_NO_deviceType"}';
	}else {

		$table = '`MembershipList`';
		$methord = 'max';
		$column = '`id`';
	
		$data -> insert($table);
		$num = $data -> selectFunc($methord,$column,$table);
		
		$table = 'MemberNickname';
		$dbcolumns = '`memberId`,`nickName`';
		// $nickName = "'Masqurin'";
		$dbValus = "$num,'$nickName'";
		$data -> insert($table,$dbcolumns,$dbValus);

		$table = 'MemberDeviceToken';
		$dbcolumns = '`memberId`,`deviceType`,`deviceToken`';
		// $deviceType = '1';
		// $deviceToken = "'aaa-bbb-ccc'";
		$dbValus = "$num,$deviceType,'$deviceToken'";
		$data -> insert($table,$dbcolumns,$dbValus);

	
		$table = 'MemberTel';
		$dbcolumns = '`memberId`,`telNumber`';
		// $tel = '0987654321';
		$dbValus = "$num,$telNumber";
		$data -> insert($table,$dbcolumns,$dbValus);


		//type 0 null 1 未綁定 2 綁定登入fb.google.自定... 3 vip 4 課長 5 土豪 6石油王....
		$table = 'MemberType';
		$dbcolumns = '`memberId`,`memberType`';
		// $type = '1';
		$dbValus = "$num,$deviceType";
		$data -> insert($table,$dbcolumns,$dbValus);

		$rtn = '{"result" : true, "memberId" : '.$num.'}';
	}
	// $jrtn = json_encode($rtn);
	echo $rtn;

 ?>