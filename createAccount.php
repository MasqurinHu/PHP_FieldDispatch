<?php 
	include "./includes/FieldDispatchDataBase.php";

	/*

	Test Command: 

	curl https://masqurin.000webhostapp.com/createAccount.php -v --header 'content-type:application/json' -X POST 
	--data 'fiveAddOne={
	"account":"TesterAccount",
	"loginMethod":"Facebook",
	"password":"abcdefg,
	"photo":"url://photo",
	"nickName":"Masqurin",
	"deviceType":"iOS",
	"DeviceToken":"a1671413bd7d904842754fe2ed571d1b72f97973ffbef3802f452adccdcfe4a8",
	"":"",
	"GroupName":"AP103",
	

	"":""}'

	*/

	//$inputJson = urldecode(file_get_contents("php://input"));
	$inputJson = $_POST['data'];

	$inputJson=str_replace('fiveAddOne=', '', $inputJson);

	if($inputJson=="")
	{
		$rtn = '{"result" : false,"errorCode":"ERR_NO_INPUT"}';
	}


	$dbValus = "'50','Iori'";
	$dbcolumns = '`memberId`,`nickName`';
	$datasheet = '`MemberNickname`';
	$data -> insert($datasheet,$dbcolumns,$dbValus);

 ?>