<?php
include "./includes/class_config.php";
include "./includes/class_database.php";
    
// Enable Debug
// error_reporting(E_ALL);
// ini_set('display_errors', True);
    
if($db == "")
{    
	$db = new sql_db($dbhost,$dbuser,$dbpasswd,$dbname);
}

if($db == false)
{
	echo "PHP Error: DB Open Fail!";
}

$db->sql_query("SET NAMES 'utf8'");
    
?>