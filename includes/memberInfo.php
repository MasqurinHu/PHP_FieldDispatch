<?php 
	if (!$data) {
		include "FieldDispatchDataBase.php";
	}

	if (!$signIn) {	
	include "SignInCheck.php";
	}

	class MemberInfo {
		
		var $id;
		var $db;

		function info () {
			$memberInfo = array();
			$memberInfo['id'] = $this -> id;
			$memberInfo['account'] = $this -> db -> get('`memberAccount`',"`MemberAccount`","`memberId` = '".$this -> id."' and `status` = '1'");

			
			
			$memberInfo['signInType'] = $this -> db -> get('`memberSingInType`',"`MemberAccount`","`memberId` = '".$this -> id."' and `status` = '1'");

			// echo '{"get" : '.json_encode($memberInfo).'}';
			// exit();


			$memberInfo['password'] = $this -> db -> get('`password`',"`MemberPassword`","`memberId` = '".$this -> id."' and `status` = '1'");
			$memberInfo['memberType'] = $this -> db -> get('`memberType`',"`MemberType`","`memberId` = '".$this -> id."' and `status` = '1'");
			$memberInfo['deviceType'] = $this -> db -> get('`deviceType`',"`MemberDeviceToken`","`memberId` = '".$this -> id."' and `status` = '1'");
			$memberInfo['deviceToken'] = $this -> db -> get('`deviceToken`',"`MemberDeviceToken`","`memberId` = '".$this -> id."' and `status` = '1'");

			$memberInfo['nickName'] = $this -> db -> get('`nickName`',"`MemberNickname`","`memberId` = '".$this -> id."' and `status` = '1'");			
			$memberInfo['mail'] = $this -> db -> get('`memberMail`',"`MemberMail`","`memberId` = '".$this -> id."' and `status` = '1'");
			$memberInfo['photo'] = $this -> db -> get('`photo`',"`MemberPhoto`","`memberId` = '".$this -> id."' and `status` = '1'");
			$memberInfo['photo'] = $this -> db -> get('`photo`',"`MemberPhoto`","`memberId` = '".$this -> id."' and `status` = '1'");
			return $memberInfo;
		}

		function __construct($id,$db){

			$this -> id = $id;
			$this -> db = $db;

		}

	}
	$Member = new MemberInfo($memberId,$data);
	$memberInfo = $Member -> info();
	$memberInfo = json_encode($memberInfo, JSON_UNESCAPED_UNICODE);

 ?>