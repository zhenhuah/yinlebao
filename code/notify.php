<?php
$db_show = new mysql ( 'phpcmsshop', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区

//$starttime = $_GET['starttime'];
//$endtime = $_GET['endtime'];
//$starttime = 1438688214;
$endtime = time();
$starttime = $endtime-86400;
$post_data =array();
$url = 'http://113.108.111.42:8833/api/hipi_game/notify_feedback';
$sql1="select app_id,packagename,status,createtime,reason from app_onlinelog where createtime>=$starttime and createtime<=$endtime and sid IS NULL";
$apk = $db_show->select( $sql1 );
foreach($apk as $v){
	$tmp = array(
		'app_id' => $v['app_id'],
		'packagename' => $v['packagename'],
		'status' => $v['status'],
		'date' => $v['createtime'],
		'reason' => $v['reason'],
	);
	$post_data['data'][] = $tmp;
}
//var_dump($post_data);
$post_data = json_encode($post_data);
echo $post_data;
$res = request_post($url, $post_data);       
print_r($res);
	

/**
     * 模拟post进行url请求
     * @param string $url
     * @param string $param
     */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
       return false;
    }
        
    $postUrl = $url;
    $curlPost = $param;
	
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
        
    return $data;
}
	
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
