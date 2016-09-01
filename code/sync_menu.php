<?php
$db_show = new mysql ( 'go3co2o', 'localhost', 'root', '86985773' );
$db_write = new mysql ( 'o2ophpcms', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区

//先清除phpcms后台的menu表原始数据
$sql1="TRUNCATE TABLE v9_menu";
$db_write->query( $sql1 );

//读取新表的menu的数据
$sql2="select * from v9_menu order by id asc";
$menu = $db_show->select( $sql2 );
foreach($menu as $v){
	$id = $v['id'];
	$name = $v['name'];
	$aliases = $v['aliases'];
	$parentid = $v['parentid'];
	$m = $v['m'];
	$c = $v['c'];
	$a = $v['a'];
	$data = $v['data'];
	$listorder = $v['listorder'];
	$display = $v['display'];
	$tips = $v['tips'];
	$type = $v['type'];
	$hyperlinks = $v['hyperlinks'];
	
	$sql3="INSERT INTO `v9_menu` (`id`, `name`, `aliases`, `parentid`, `m`, `c`, `a`, `data`, `listorder`, `display`, `tips`, `type`, `hyperlinks`) VALUES ('$id', '$name', '$aliases', '$parentid', '$m', '$c', '$a', '$data', '$listorder', '$display', '$tips', '$type', '$hyperlinks')";
	$db_write->query( $sql3 );
}
$db_show->close ();	
$db_write->close ();
echo 'ok';
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
