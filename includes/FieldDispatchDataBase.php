<?php 
include "class_config.php";

class dbData{
		var $db;

		function insert($datasheet,$dbcolumns = "",$dbValus = ""){
			$this-> db-> query("insert into $datasheet($dbcolumns) values($dbValus)");
		}

		function insertAndReportId($table,$dbcolumns = "",$dbValus = ""){
			// echo "insert into $table($dbcolumns) values($dbValus)<br>";
			$this-> db-> query("insert into $table($dbcolumns) values($dbValus)");
			return $this-> db-> query("SELECT LAST_INSERT_ID()")->fetch();
		}

		function delete($datasheet,$dbcolumns,$dbValus){
			$this-> db-> query("delete from $datasheet where $dbcolumns = $dbValus");
			// unlink("gusetbook_photo/".$_GET["id"]);
		}

		function update($table,$dbcolumns,$dbValus,$wheredbcolumns,$wheredbValus){
			// echo "update $table
			// 	set $dbcolumns = $dbValus
			// 	where $wheredbcolumns = $wheredbValus";
			$this-> db-> query("update $table
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

		function  selectFunc($column,$table,$condition,$methord = ""){
			return $this-> db-> query("select $methord($column) from $table where $condition")->fetchColumn(0);
		}

		function selectLastAdd($column,$table,$condition){
			// echo "select $column from $table where $condition order by `createTime` desc<br>";
			return $this-> db-> query("
				select $column from $table where $condition order by `createTime` desc ")->fetchColumn(0);
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

		function __construct($dbname,$dbhost,$dbuser,$dbpasswd){
			//pdo sql類別
			$this-> db = new pdo("mysql: host = $dbhost; port = 3306","$dbuser","$dbpasswd");
			$this-> db-> query("set names 'utf8'");
			$this-> db-> query("use `$dbname`");
		}
	}

	$data = new dbData($dbname,$dbhost,$dbuser,$dbpasswd);
	
	
 ?>