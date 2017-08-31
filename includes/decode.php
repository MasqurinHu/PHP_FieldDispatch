<?php 
	//$inputJson = urldecode(file_get_contents("php://input"));

    	

	$inputJson = $_POST['FieldDispatch'];

	$inputJson=str_replace('FieldDispatch=', '', $inputJson);

	$inputArray=json_decode($inputJson, true);
	



	//		var_dump($inputArray);

	//必要資訊 驗證身分
	$memberAccount = $inputArray['account'];
	$memberSingInType = $inputArray['signInType'];
	$memberType = $inputArray['memberType'];
	$memberId = $inputArray['id'];
	$password = $inputArray['password'];
	$deviceType = $inputArray['deviceType'];	//0 null 1 iOS 2 Android
	$deviceToken = $inputArray['deviceToken'];

	//一般使用者資訊
	$nickName = $inputArray['nickName'];
	$memberMail = $inputArray['mail'];
	$photo = $inputArray['photo'];

	//即時回報資訊
	$lat = $inputArray['lat'];
	$lon = $inputArray['lon'];
	$memberStatus = $inputArray['memberStatus'];
	
	//建立任務資料
	$createMission = $inputArray['createMission'];



    





 ?>