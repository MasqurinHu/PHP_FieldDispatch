<?php 
	if (!$data) {
		include "FieldDispatchDataBase.php";
	}

	if (!$func) {
		include "Func.php";	
    }

	// 檢查/查詢會員資訊 
	// 測試輸入帳號 需求資訊
	// $memberType = '2';
	// $memberAccount = '115086560858057731121';
	// $memberSingInType = '2';
	// $password = 'aaa';
	// $memberId = '2';
	// $deviceType = '1';	//0 null 1 iOS 2 Android
 //    $deviceToken = 'deviceToken';
    // ︿︿︿︿︿︿︿︿︿︿

    if (!$memberId) {
        echo '{"result" : false , "errorCode" : "ERR_NO_memberId" }';
        exit();
    }

    //type 0 null 1 未綁定 2 綁定登入fb.google.自定... 3 vip 4 課長 5 土豪 6石油王....
    //先判斷 會員種類 memberType > 1 為非簡易帳號 需做帳號驗證 簡易帳號只檢查id
    if ($memberType > 1) {
    	// 檢查 帳號是否啟用， 會員編號 帳號種類 及帳號名稱 是否與資料庫相符
    	$accountList = $data -> selcetList('`memberAccount`','`MemberAccount`',"`status` = 1 and `memberId` = ".$memberId." and `memberSingInType` = ".$memberSingInType." and `memberAccount` = '".$memberAccount."'");
        

        // echo '{"why" : '.$inputJson.'}';
        // exit();

        if ($accountList["result"] == true) {
            $accountList = $accountList["data"][0][0];
        } else {
            echo '{"result" : false , "errorCode" : "ERR_NO_ACCOUNT_OR_ID"}';
            exit();
        }

    	$passwordList = $data -> selcetList('`password`','MemberPassword',"`status` = 1 and `password` = '".$password."' and `memberId` = ".$memberId);
        if ($passwordList["result"] == true) {
            $passwordList = $passwordList["data"][0][0];
        } else {
            echo '{"result" : false , "errorCode" : "ERR_PASSWORD"}';
            exit();
        }

    } else {
    	//檢查 會員編號 會員種類 
    	$memberTypeList = $data -> selcetList('`memberType`','MemberType',"`status` = 1 and `memberType` = '".$memberType."' and `memberId` = ".$memberId);

        if ($memberTypeList["result"] == true) {
            $memberTypeList = $memberTypeList["data"][0][0];
        } else {
            echo '{"result" : false , "errorCode" : "SIGN_ERR_MEMBER_TYPE"}';
            exit();
        }


    	//檢查 裝置種類 裝置金鑰 會員編號
    	$deviceTokenList = $data -> selcetList('`MemberDeviceToken`','MemberDeviceToken',"`status` = 1 and `deviceType` = '".$deviceType."' and `memberId` = ".$memberId." and `deviceToken` = '".$deviceToken."'");

        if ($deviceTokenList["result"] == true) {
            $deviceTokenList = $deviceTokenList["data"][0][0];
        } else {
            echo '{"result" : false , "errorCode" : "ERR_DEVICE_TOKEN"}';
            exit();
        }
    }

    $signIn = true;

 ?>