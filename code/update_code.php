<?php
$db_show = new mysql ( 'phpcmsshop', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区
/*
$type = $_GET['type'];
$last_time = $_GET['last_time'];  //上一次同步时间（开始时间）
$cur_time = $_GET['cur_time'];	  //当前时间（结束时间）
*/
$last_time = 1438328146;
$cur_time = time();
$url = 'http://topwayxv.zsitv.com:8000/api/hipi_game/sync_data?last_time='.$last_time.'&cur_time='.$cur_time.'&type=all';
$resurt = file_get_contents($url);
$resurt = json_decode($resurt,true);
foreach($resurt['data'] as $v){
	$package_name = $v['package_name'];
	$version_code = $v['version']['version_code'];

	$sql1="update app set versioncode='$version_code' where packagename='$package_name'";
	$db_show->query ( $sql1 );
	
	$sql2="update t_app_all_list set version='$version_code' where packagename='$package_name'";
	$db_show->query ( $sql2 );
	echo $package_name."\n";
}

echo "ok";
$db_show->close ();
class mysql {
	private $query;
	private $db;
	private $conn = '';
	function __construct($db, $DBHOST, $DBUSER, $DBPASS, $char = 'utf8', $four = TRUE) {
		$this->db = $db;
		$conn = mysql_connect ( $DBHOST, $DBUSER, $DBPASS, $four ) or die ( $this->error () );
		$this->conn = $conn;
		mysql_select_db ( $this->db, $conn ) or die ( "连接失败" . $this->db );
		mysql_query ( "SET NAMES " . $char );
	}
	function query($sql) {
		$query = mysql_query ( $sql, $this->conn ) or die ( $this->error () );
		return $query;
	}
	function row($sql) {
		$query = $this->query ( $sql );
		return mysql_fetch_assoc ( $query );
	}
	
	function select($sql) {
		$result = array ();
		$query = $this->query ( $sql );
		while ( $row = mysql_fetch_array ( $query ) ) {
			$result [] = $row;
		}
		return $result;
	}
	
	function affected_rows() {
		return mysql_affected_rows ( $this->conn );
	}
	function error() {
		return mysql_error ();
	}
	function close() {
		return @ mysql_close ( $this->conn );
	}
	function __destruct() {
	}
}
?>
