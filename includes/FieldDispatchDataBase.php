<?php 
include "class_config.php";

class dbData{
		var $db;

		var $count = 0;

		function autoCommitClose () {
			$this -> db -> query ("SET AUTOCOMMIT = 0");
		}

		function autoCommitOpen () {
			$this -> db -> query ("SET AUTOCOMMIT = 1");
		}

		function rollback () {
			$this -> db -> query ("rollback");
		}

		function insert($datasheet,$dbcolumns = "",$dbValus = ""){

			// echo '{"insert" : "insert into '.$datasheet.'('.$dbcolumns.') values('.$dbValus.')"}';
			$this-> db-> query("insert into $datasheet($dbcolumns) values($dbValus)");
			// exit();
		}

		function insertAndReportId($table,$dbcolumns = "",$dbValus = ""){
			// echo '{" 為什麼" : "'."insert into $table($dbcolumns) values($dbValus)".'"}';exit();
			$this-> db-> query("insert into $table($dbcolumns) values($dbValus)");
			return $this-> db-> query("SELECT LAST_INSERT_ID()")->fetch();
		}

		function delete($datasheet,$dbcolumns,$dbValus){
			$this-> db-> query("delete from $datasheet where $dbcolumns = $dbValus");
			// unlink("gusetbook_photo/".$_GET["id"]);
		}

		function getUpdate ($table,$column,$where = "") {

			if ($where != "") {
				$where = "where ".$where;
			}

			$this -> db -> query("update $table set $column $where");

			// echo '{"getUpdata" : "update '.$table.' set '.$column.' '.$where.'"}';
			// exit();
		}

		function prepareUpdate ($table,$column,$arr,$where = "") {

			if ($where != "") {
				$where = "where ".$where;
			}

			$this -> db -> prepare("update $table set $column $where") -> execute($arr);

			// echo '{"prepareUpdate" : "update '.$table.' set '.$column.' '.$where.'" ,"arr = " : '.json_encode($arr).'}';
			// exit();
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

		function getMemberStatus ($column,$table,$condition) {

			if ($condition != "") {
				$condition = $condition." and";
			}

			// echo '{"func" : "'."select $column from $table where $condition `status` > 0 and `statusTime` > UNIX_TIMESTAMP( NOW() - INTERVAL 5 MINUTE ) order by `id` DESC".'"}';
			// exit();

			return $this -> db -> query("
				select $column from $table where $condition `status` > 0 and `statusTime` > UNIX_TIMESTAMP( NOW() - INTERVAL 5 MINUTE ) order by `id` DESC 
				") -> fetchColumn(0);
		}

		function  get ($column,$table,$condition) {

			// echo '{"get" : "'."select $column from $table where $condition order by `id` desc".'"}';
			// exit();

			return $this-> db-> query("
				select $column from $table where $condition order by `id` desc 
				") -> fetchColumn(0);
		}

		function getListNoStatus ($column,$table,$condition) {

			// echo '{"test" : "'."select $column from $table where $condition order by `id` asc ".'"}';
			// exit();

			return $this-> db-> query("
				select $column from $table where $condition order by `id` asc 
				")->fetchAll();
		}

		function getList ($column,$table,$condition) {

			if ($condition != "") {
				$condition = $condition." and";
			}

			return $this-> db-> query("
				select $column from $table where $condition `status` > 0 order by `id` asc 
				")->fetchAll();
		}

		function  selcetList ($column,$table,$condition) {
			// echo '{"why" : "select '.$column.' from '.$table.' where '.$condition.' and `status` = 1 order by `id` asc"}';
			// exit();

			if ($condition != "") {
				$condition = $condition." and";
			}

			$check = array();
			$check["result"] = false;

			$scapegoat = $this-> db-> query("
				select $column from $table where $condition `status` > 0 order by `id` asc 
				")->fetchAll();

			if (count($scapegoat) != 0) {
				$check["result"] = true;
				$check["data"] = $scapegoat;
			}
			return 	$check;
		}

		function  selcetListNoStatus ($column,$table,$condition) {
			// echo "select $column from $table where $condition order by `id` asc <br>";
			$scapegoat = $this-> db-> query("
				select $column from $table where $condition order by `id` asc 
				")->fetchAll();

			$check = array();
			$check["result"] = false;
			if (count($scapegoat) != 0) {
				$check["result"] = true;
				$check["data"] = $scapegoat;	
			}
			return 	$check;

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
			// $this-> db-> query("set GLOBAL time_zone = '+08:00'");
			$this-> db-> query("use `$dbname`");
		}
	}

	$data = new dbData($dbname,$dbhost,$dbuser,$dbpasswd);
	
	
 ?>