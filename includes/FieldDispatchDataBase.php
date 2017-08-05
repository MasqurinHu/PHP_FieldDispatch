<?php 
include "class_config.php";

class dbData{
		var $db;

		function InsertId(){
			$this-> db-> query("insert into `MembershipList`() 
				values()");
			return $this-> db-> query("SELECT LAST_INSERT_ID()")->fetch();
		}

		function insert($datasheet,$dbcolumns = "",$dbValus = ""){
			$this-> db-> query("insert into $datasheet($dbcolumns) values($dbValus)");
		}

		function insertAndReportId($table,$dbcolumns = "",$dbValus = ""){
			$this-> db-> query("insert into $table($dbcolumns) values($dbValus)");
			return $this-> db-> query("SELECT LAST_INSERT_ID()")->fetch();
		}

		function delete($datasheet,$dbcolumns,$dbValus){
			$this-> db-> query("delete from $datasheet where $dbcolumns = $dbValus");
			// unlink("gusetbook_photo/".$_GET["id"]);
		}

		function update($datasheet,$dbcolumns,$dbValus,$wheredbcolumns,$wheredbValus){
			$this-> db-> query("update $datasheet
				set $dbcolumns = $dbValus
				where $wheredbcolumns = $wheredbValus");


			// $_POST["id"] = $_GET["id"];
			// $this-> db-> prepare("update `guestbook`
			// 	set `content` = :content
			// 	where `id` = :id")-> execute($_POST);
			// copy(
			// 	$_FILES["photo"]["tmp_name"],
			// 	"gusetbook_photo/".$_GET["id"]
			// 	);
		}

		

		function  selectFunc($methord,$column,$table){
			return $this-> db-> query("select $methord($column) from $table")->fetchColumn(0);
		}

		function selectLimit($a,$b){
			return $this-> db-> query("
				select `guestbook`.*,`member`.`user`,`member`.`email` from 
				`guestbook` inner join `member` 
				on `guestbook`.`member_id`=`member`.`id`
				order by `creat_time` desc limit $a,$b")->fetchAll();
		}

		// function selectAll(){
		// 	return $this-> db-> query("select * from `guestbook`")->fetchAll();
		// }

		function selectAllCount(){
			return $this-> db-> query("select count(`id`) from `guestbook`")->fetchColumn(0);
		}

		function selectFetch($id){
			return $this-> db-> query("
				select `guestbook`.*,`member`.`user`,`member`.`email` from 
				`guestbook` inner join `member` 
				on `guestbook`.`member_id`=`member`.`id` where `guestbook`.`id` = '$id'")->fetch();
		}

		function pageItem(){
			$_SESSION["pageItem"]=$_GET["item"];
		}

		function __construct($dbname,$dbhost,$dbuser,$dbpasswd){
			//pdo sql類別
			$this-> db = new pdo("mysql: host = $dbhost; port = 3306","$dbuser","$dbpasswd");
			$this-> db-> query("set names 'utf8'");
			$this-> db-> query("use `$dbname`");
		}
	}

	$data = new dbData($dbname,$dbhost,$dbuser,$dbpasswd);
	
	
 ?>