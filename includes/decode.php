<?php 
	//$inputJson = urldecode(file_get_contents("php://input"));
	$inputJson = $_POST['FieldDispatch'];

	$inputJson=str_replace('FieldDispatch=', '', $inputJson);

	//		echo "Input:".$inputJson;

	$inputArray=json_decode($inputJson, true);
	
	//		var_dump($inputArray);
 ?>