<?php
$db_write = new mysql ( 'phpcms', 'localhost', 'root', '86985773' );
$db_show = new mysql ( 'go3c', 'localhost', 'root', '86985773' );
$db_shop = new mysql ( 'shop', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' );
/*
//phpcms库的首字母生成
$sql1="select * from v9_video where ((column_id in (3,4,7,8,9) and ispackage=1) or column_id=5 or column_id=6) and spells=''";
$sv = $db_write->select ( $sql1 );
foreach($sv as $v){
	$id = $v['id'];
	$vid = $v['asset_id'];
	$zh = strip_tags($v['title']);
	//$zh = preg_replace('((?=[\x21-\x7e]+)[^A-Za-z0-9])', '', $v['title']); 
	$ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
	
	$sql2="update v9_video set spells='$ret' where id='$id'";
	echo $sql2.'\n';
	$db_write->query ( $sql2 );
}
*/
//接口的首字母生成
$sql3="select * from video where ((column_id in (3,4,7,8,9) and `parent_id` IS NULL) or column_id=5 or column_id=6) and spell IS NULL";
$svv = $db_show->select ( $sql3 );
foreach($svv as $v){
	$vid = $v['vid'];
	$zh = strip_tags($v['name']);
	//$zh = preg_replace('((?=[\x21-\x7e]+)[^A-Za-z0-9])', '', $v['title']); 
	$ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
	
	$sql4="update video set spell='$ret' where vid ='$vid'";
	echo $sql4.'\n';
	$db_show->query ( $sql4 );
}
//生成演员首字母
$sql5="select * from actor where spell IS NULL";
$sva = $db_show->select ( $sql5 );
foreach($sva as $v){
	$id = $v['id'];
	$zh = strip_tags($v['name']);
	//$zh = preg_replace('((?=[\x21-\x7e]+)[^A-Za-z0-9])', '', $v['title']); 
	$ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
	
	$sql6="update actor set spell='$ret' where id ='$id'";
	echo $sql6.'\n';
	$db_show->query ( $sql6 );
}
//生成应用商店apk的首字母
$sql7="select * from app where spell IS NULL";
$svp = $db_shop->select ( $sql7 );
foreach($svp as $vp){
	$app_id = $vp['app_id'];
	$zh = strip_tags($vp['app_name']);
	$ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getfirstchar($s2);
        }else{
            $ret .= $s1;
        }
    }
	
	$sql8="update app set spell='$ret' where app_id ='$app_id'";
	echo $sql8.'\n';
	$db_shop->query ( $sql8 );

	$sql9="update t_app_all_list set spell='$ret' where app_id ='$app_id'";
	echo $sql9.'\n';
	$db_shop->query ( $sql9 );
}
function getfirstchar($s0){  
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 and $asc <= -20284) return "a";
	if ($asc >= -20283 and $asc <= -19776) return "b";
	if ($asc >= -19775 and $asc <= -19219) return "c";
	if ($asc >= -19218 and $asc <= -18711) return "d";
	if ($asc >= -18710 and $asc <= -18527) return "e";
	if ($asc >= -18526 and $asc <= -18240) return "f";
	if ($asc >= -18239 and $asc <= -17923) return "g";
	if ($asc >= -17922 and $asc <= -17418) return "h";
	if ($asc >= -17417 and $asc <= -16475) return "j";
	if ($asc >= -16474 and $asc <= -16213) return "k";
	if ($asc >= -16212 and $asc <= -15641) return "l";
	if ($asc >= -15640 and $asc <= -15166) return "m";
	if ($asc >= -15165 and $asc <= -14923) return "n";
	if ($asc >= -14922 and $asc <= -14915) return "o";
	if ($asc >= -14914 and $asc <= -14631) return "p";
	if ($asc >= -14630 and $asc <= -14150) return "q";
	if ($asc >= -14149 and $asc <= -14091) return "r";
	if ($asc >= -14090 and $asc <= -13319) return "s";
	if ($asc >= -13318 and $asc <= -12839) return "t";
	if ($asc >= -12838 and $asc <= -12557) return "w";
	if ($asc >= -12556 and $asc <= -11848) return "x";
	if ($asc >= -11847 and $asc <= -11056) return "y";
	if ($asc >= -11055 and $asc <= -10247) return "z";
    return null;
}
echo "ok";
$db_write->close ();
class mysql {
	private $query;
	private $db;
	private $conn = '';
	function __construct($db, $DBHOST, $DBUSER, $DBPASS, $char = 'utf8', $four = TRUE) {
		$this->db = $db;
		$conn = mysql_connect ( $DBHOST, $DBUSER, $DBPASS, $four ) or die ( $this->error () );
		$this->conn = $conn;
		mysql_select_db ( $this->db, $conn ) or die ( "杩炴帴澶辫触" . $this->db );
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
