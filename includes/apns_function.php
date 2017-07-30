
<?php
    
function raw_json_encode($input) {

    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );

}       

function doPushToAll($db,$message,$badge,$sound,$pemfile,$sendername,$groupname)
{
//	echo "groupname:".$groupname;
	$sql = "select * from DeviceTokenList where GroupName='$groupname'";
	$res = $db->sql_query($sql);
	$num = $db->sql_numrows($res);
	
//	echo "SQL Command:".$sql;
	
	if($num <= 0)
	{
		return;
	}	
	
//	echo "Total Devices:".$num;

	// Construct the notification payload
	$body = array();
	
	$alert = array('title' => 'Message From '.$sendername,'body' => $message);
	
	$body['aps'] = array('alert' => $alert);
	if ($badge)
		$body['aps']['badge'] = $badge;
	if ($sound)
		$body['aps']['sound'] = $sound;
	
	
	/* End of Configurable Items */
	
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $pemfile);
	
	$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
	// for production change the server to ssl://gateway.push.apple.com:2195	
	//$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

	if (!$fp) {
		echo "Failed to connect $err $errstr.";
		return;
	}
	else {
//		echo "Connection OK";
	}
	

    while($row = $db->sql_fetchrow($res))
    {		
    	$deviceToken = $row['DeviceToken'];
    	if($sendername !=  $row['UserName'] && $deviceToken != "")
    	{    
			$payload = raw_json_encode($body);//json_encode($body);
			$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
//			echo "sending message :" . $payload . "<br>";
			fwrite($fp, $msg);
		}
	
	}
		
	fclose($fp);
	
}


?>
