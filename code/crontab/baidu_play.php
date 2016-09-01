<?php
$db_phpcms = new mysql ( 'phpcms', 'localhost', 'root', '86985773' );
$db_go3c = new mysql ( 'go3c', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区

	$sql1="select * from v9_video where column_id='9' and ispackage='0'";
	$video = $db_phpcms->select ( $sql1 );
	foreach($video as $v){
		$asset_id = $v['asset_id'];
		$channel = $v['channel'];

		echo $channel.'\n';
		$url = 'http://www.go3c.tv:8060/test/txt/bdmusic.php?sid='.$channel;
		$ss = file_get_contents($url);

		echo $ss.'\n';

		$sql2="update v9_video_content set path='$ss' where asset_id='$asset_id'";
		$db_phpcms->query ( $sql2 );

		$sql3="update video_play_info set play_url='$ss' where vid='$asset_id'";
		$db_go3c->query ( $sql3 );

	}

$db_phpcms->close ();
$db_go3c->close ();
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
