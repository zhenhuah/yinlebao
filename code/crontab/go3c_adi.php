<?php
$db_write = new mysql ( 'phpcms', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区
/*
$type=$_GET['type'];
if(empty($type)){
	echo 'data is error !';exit;
}
*/
//$type=$_GET['type'];
$type = 'tv';
if($type == 'movie'){   //电影
	$urlchan='http://www.go3c.tv:8060/go3ccms/xml/movieADI.xml';
	$s = file_get_contents($urlchan) ;
	$xml = simplexml_load_string($s) ;
	foreach($xml->Metadata->AMS as $c){
		$adi_id = $c['Asset_ID'] ;
		$Asset_Name = $c['Asset_Name'] ;
		//获取所属栏目
		foreach($xml->Asset->Metadata->App_Data as $v){
			$Name = $v['Name'] ;		
			if($Name=='Show_Type'){
				$cloum = $v['Value'] ;
				if($cloum =='program'){		//电影
					$column_id = 5;
				}elseif($cloum =='MOD'){	//电视剧
					$column_id = 4;
				}elseif($cloum =='column'){	//综艺
					$column_id = 3;
				}
			}
		}
		//获取导演
		$Dire = array();
		foreach($xml->Asset->Asset->Metadata->App_Data as $v){
			$Name = $v['Name'] ;
			if($Name=='Director'){
				array_push($Dire, $v['Value']);
			}
			//break;
		}
		$Director= implode(',',$Dire);
		//匹配本地的数据库
		$where = " 1 and";
		if(!empty($Asset_Name)){
			$where.=" title='$Asset_Name' and";
		}
		if(!empty($column_id)){
			$where.=" column_id='$column_id' and";
		}
		if(!empty($Director)){
			$where.=" director like '%$Director%'";
		}
		$sql1="select * from v9_video where".$where;
		$svideo = $db_write->row ( $sql1 );
		if(!empty($svideo)){
			$asset_id = $svideo['asset_id'];
			$sql2="update v9_video set push='1',adi_id='$adi_id' where asset_id='$asset_id'";
			$db_write->query ( $sql2 );
		}
	}
}elseif($type == 'drama'){	//电视剧
	$urlchan='http://www.go3c.tv:8060/go3ccms/xml/dramaADI.xml';
	$s = file_get_contents($urlchan) ;
	$xml = simplexml_load_string($s) ;
	foreach($xml->Metadata->AMS as $c){
		$adi_id = $c['Asset_ID'] ;
		$Asset_Name = $c['Asset_Name'] ;
		//获取总集所属栏目
		foreach($xml->Asset->Metadata->App_Data as $v){
			$Name = $v['Name'] ;		
			if($Name=='Show_Type'){
				$cloum = $v['Value'] ;
				if($cloum =='program'){		//电影
					$column_id = 5;
				}elseif($cloum =='MOD'||$cloum=='series'){	//电视剧
					$column_id = 4;
				}elseif($cloum =='column'){	//综艺
					$column_id = 3;
				}
			}
		}
		//获取导演
		$Dire = array();
		foreach($xml->Asset->Asset->Metadata->App_Data as $v){
			$Name = $v['Name'] ;
			if($Name=='Director'){
				array_push($Dire, $v['Value']);
			}
			//break;
		}
		$Director= implode(',',$Dire);
		//匹配本地的数据库
		$where = " 1 and";
		if(!empty($Asset_Name)){
			$where.=" title='$Asset_Name' and";
		}
		if(!empty($column_id)){
			$where.=" column_id='$column_id' and";
		}
		if(!empty($Director)){
			$where.=" director like '%$Director%'";
		}
		$sql1="select * from v9_video where".$where;
		$svideo = $db_write->row ( $sql1 );
		if(!empty($svideo)){
			$asset_id = $svideo['asset_id'];
			$sql2="update v9_video set push='1',adi_id='$adi_id' where asset_id='$asset_id'";
			$db_write->query ( $sql2 );
			//获取电视剧分集的相关信息
			foreach($xml->Asset as $vs){
				foreach($vs->Metadata as $vs2){
					foreach($vs2->AMS as $vs4){
						$fjvid = $vs4['Asset_ID'];		//获取分集的id
					}
					foreach($vs2->App_Data as $vs3){
						if($vs3['Name']== 'Chapter'){	//判断电视剧的分集的集数
							$number = $vs3['Value'];
							$sql3="select * from v9_video where column_id='$column_id' and episode_number='$number' and title like '%$Asset_Name%'";
							$fjvideo = $db_write->row ( $sql3 );
							if(!empty($fjvideo)){
								$asset_id = $fjvideo['asset_id'];
								$sql4="update v9_video set push='1',adi_id='$fjvid' where asset_id='$asset_id'";
								$db_write->query ( $sql4 );
							}
						}
					}
				}
			}
		}
	}
}elseif($type == 'tv'){	//综艺
	$urlchan='http://www.go3c.tv:8060/go3ccms/xml/tvADI.xml';
	$s = file_get_contents($urlchan) ;
	$xml = simplexml_load_string($s) ;
	foreach($xml->Metadata->AMS as $c){
		$adi_id = $c['Asset_ID'] ;
		$Asset_Name = $c['Asset_Name'] ;
		//获取总集所属栏目
		foreach($xml->Asset->Metadata->App_Data as $v){
			$Name = $v['Name'] ;		
			if($Name=='Show_Type'){
				$cloum = $v['Value'] ;
				if($cloum =='program'){		//电影
					$column_id = 5;
				}elseif($cloum =='MOD'||$cloum=='series'){	//电视剧
					$column_id = 4;
				}elseif($cloum =='column'){	//综艺
					$column_id = 3;
				}
			}
		}
		//匹配本地的数据库
		$where = " 1 and";
		if(!empty($Asset_Name)){
			$where.=" title like '$Asset_Name%' and";
		}
		if(!empty($column_id)){
			$where.=" column_id='$column_id' ";
		}
		$sql1="select * from v9_video where".$where;
		$svideo = $db_write->row ( $sql1 );	
		if(!empty($svideo)){
			$asset_id = $svideo['asset_id'];
			$sql2="update v9_video set push='1',adi_id='$adi_id' where asset_id='$asset_id'";
			$db_write->query ( $sql2 );
			//获取综艺节目的分集
			foreach($xml->Asset as $vs){
				foreach($vs->Metadata as $vs2){
					foreach($vs2->AMS as $vs4){
						$fjvid = $vs4['Asset_ID'];		//获取分集的id
					}
					foreach($vs2->App_Data as $vs3){
						if($vs3['Name']== 'Issue_Number'){
							$number = $vs3['Value'];
						}
						$length = strlen($number);
						if(empty($length)||$length<4){   //判断分集数是数字还是日期(20130129)
							if($vs3['Name']== 'Proper_Title'){  //获取分集的期数(第几期)
								$da = $vs3['Value'];
								$patterns = "/(\\d{4})-(\\d{1,2})-(\\d{1,2})/";
								preg_match_all($patterns,$da,$arr);
								$lengthf = strlen($arr[0][0]);
								if($lengthf<8){
									//$episode_number = date("Y-m-d",time());
									continue;
								}else{
									$episode_number = $arr[0][0];
								}								
							}
						}else{
							$episode_number = $number;
						}
					}
					if(empty($episode_number)) continue;
					$sql3="select * from v9_video where column_id='$column_id' and episode_number='$episode_number' and title like '%$Asset_Name%'";
					echo $sql3."\n";
					echo $fjvid."\n";
					$fjvideo = $db_write->row ( $sql3 );
					
					if(!empty($fjvideo)){
						$asset_id = $fjvideo['asset_id'];
						$sql4="update v9_video set push='1',adi_id='$fjvid' where asset_id='$asset_id'";
						$db_write->query ( $sql4 );
					}
					
				}
			}
		}
	}
}

//获取远程文件..+重试
function ifile_get_contents($f,$rec=3,$sleep=1){
	$s = '' ;
	$i = 0 ;
$context = stream_context_create(array(
     'http' => array(
      'timeout' => 3000 //超时时间，单位为秒
     ) 
));  
	while($s == ''){
		$s = file_get_contents($f,0,$context) ;
		if($s != '') break ;
		$i++ ;
		sleep($sleep) ;
		if($i >= $rec) return false ;
	}
	return $s ;
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
