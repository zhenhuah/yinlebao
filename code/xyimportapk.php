<?php
$db_show = new mysql ( 'phpcmsshop', 'localhost', 'root', '86985773' );
date_default_timezone_set ( 'PRC' ); // 设定时区
/*
$type = $_GET['type'];
$last_time = $_GET['last_time'];  //上一次同步时间（开始时间）
$cur_time = $_GET['cur_time'];	  //当前时间（结束时间）
*/
//小y数据导入
$last_time = 0;
$cur_time = time();
$url = 'http://www.stvgame.com:8899/mis/web/gdAction_export.action?last_time='.$last_time.'&cur_time='.$cur_time.'&type=all';
//echo $url;die();
$resurt = file_get_contents($url);
$resurt = json_decode($resurt,true);
foreach($resurt['data'] as $v){
	$sid = $v['id'];
	$title = $v['title'];
	$package_name = $v['package_name'];
	$producer = $v['producer'];
	$price_type = $v['price_type'];
	$pingying = $v['abbr'];
	$aptype = $v['type'];
	$desc = mysql_escape_string($v['desc']);
	
	$controller = $v['environment']['controller_type'];
	$platform = $v['environment']['platform'];
	$os = $v['environment']['os'];
	$internet = $v['environment']['internet'];

	$release_note = $v['version']['release_note'];
	$version = $v['version']['version'];
	$version_code = $v['version']['version_code'];
	
	foreach($v['catogories'] as $v1){
		$catogoriesname = $v1['name'];
	}
	//foreach($v['tags'] as $v2){
	//	$tagsname = $v2['name'];
	//}
	foreach($v['languages'] as $v5){
		$languages = $v5['name'];
	}
	$create_time = date("Y-m-d H:i:s",time());
	$update_time = date("Y-m-d H:i:s",time());
	if($aptype=='game'){
		$channel_cat_id = 11;
	}elseif($aptype=='edu'){
		$channel_cat_id = 5;
	}elseif($aptype=='app'){
		$channel_cat_id = 3;
	}
	$tagsname = '';
	//主体信息写入数据表
	$sql1="select app_id from app where packagename='$package_name'";
	$ss1 = $db_show->row ( $sql1 );
	if(empty($ss1['app_id'])){
		$sql2="INSERT INTO `app` (`sid`, `app_name`, `spell`, `app_desc`, `channel_cat_id`, `language`, `packagename`, `os_ver`, `tag`, `view_count`, `download_count`, `create_time`, `update_time`, `status`, `channel`, `type`, `version`, `price`, `owner`, `controller_type`, `versioncode`)VALUES
('$sid', '$title', '$pingying', '$desc', '$channel_cat_id', '$languages', '$package_name', '$os', '$tagsname', 12, 238463, '$create_time', '$update_time', 1, '$aptype', '$aptype', '$version', '$price_type', '$producer', '$controller', '$version_code')";
		//echo $sql2."\n";
		$db_show->query ( $sql2 );
		//$app_id = $db_show->insert_id;
		$sql5="select * from app where packagename='$package_name'";
		$ss5 = $db_show->row ( $sql5 );
		$app_id = $ss5['app_id'];
		//写入apk的下载链接
		foreach($v['file'] as $v3){
			$url = $v3['url'];
			$md5 = $v3['md5'];
			//$header_array = get_headers($url, true);
			//$size = $header_array['Content-Length'];
			$size = $v3['size'];
			$sql3="INSERT INTO `app_download_info` (`app_id`, `term_id`, `os_type`, `install_file`) VALUES($app_id, 'all', 1, '$url')";
			//echo $sql3."\n";
			$db_show->query ( $sql3 );
			/*$arr = explode("/",$url);
			$tourl = '/home/wwwroot/default/apk/topway/'.$arr[7];
			#下载文件
			$file = new HttpDownload(); # 实例化类
			$file->OpenUrl($url); # 远程文件地址
			$file->SaveToBin($tourl); # 保存路径及文件名
			$file->Close(); # 释放资源
			*/
		}
		//更新MD5
		$sql6="update `app` set file_hash='$md5',file_size='$size' where app_id='$app_id'";
		$db_show->query ( $sql6 );
		//写入apk的图片
		foreach($v['images'] as $v4){
			$type = $v4['type'];
			if($aptype=='game'){
				if($type=='icon'){
					$type=102;
				}elseif($type=='small'){
					$type=103;
				}elseif($type=='big'){
					$type=111;
				}
			}else{
				if($type=='icon'){
					$type=122;
				}elseif($type=='small'){
					$type=102;
				}elseif($type=='big'){
					$type=124;
				}
			}
			
			$img = $v4['img'];
			$sql4="INSERT INTO `app_image` (`app_id`, `term_id`, `os_type`, `seq_number`, `image_file`, `image_type`) VALUES($app_id, 0, 0, 0, '$img', '$type')";
			//echo $sql4."\n";
			$db_show->query ( $sql4 );
			/*$arr1 = explode("/",$img);
			$tourl1 = '/home/wwwroot/default/apk/topway/'.$arr1[7];
			#下载文件
			$file = new HttpDownload(); # 实例化类
			$file->OpenUrl($img); # 远程文件地址
			$file->SaveToBin($tourl1); # 保存路径及文件名
			$file->Close(); # 释放资源
			*/
		}
		//导入的应用记录导入日志
		$tt = time();
		$sql7="INSERT INTO `app_onlinelog` (`app_id`, `sid`, `packagename`, `status`, `createtime`, `reason`, `source`) VALUES ('$app_id', '$sid', '$package_name', 'import', '$tt', '导入', 'xy')";
		$db_show->query ( $sql7 );
		echo 'one ok!'."\n";
	}else{
		echo '111'."\n";
	}
	//var_dump($catogoriesname);var_dump($title);var_dump($package_name);
}


/**
 * 下载远程文件类支持断点续传
 */
class HttpDownload {
    private $m_url = "";
    private $m_urlpath = "";
    private $m_scheme = "http";
    private $m_host = "";
    private $m_port = "80";
    private $m_user = "";
    private $m_pass = "";
    private $m_path = "/";
    private $m_query = "";
    private $m_fp = "";
    private $m_error = "";
    private $m_httphead = "" ;
    private $m_html = "";
  
    /**
     * 初始化
     */
    public function PrivateInit($url){
        $urls = "";
        $urls = @parse_url($url);
        $this->m_url = $url;
        if(is_array($urls)) {
            $this->m_host = $urls["host"];
            if(!empty($urls["scheme"])) $this->m_scheme = $urls["scheme"];
            if(!empty($urls["user"])) $this->m_user = $urls["user"];
            if(!empty($urls["pass"])) $this->m_pass = $urls["pass"];
            if(!empty($urls["port"])) $this->m_port = $urls["port"];
            if(!empty($urls["path"])) $this->m_path = $urls["path"];
            $this->m_urlpath = $this->m_path;
            if(!empty($urls["query"])) {
                $this->m_query = $urls["query"];
                $this->m_urlpath .= "?".$this->m_query;
            }
        }
    }
  
    /**
    * 打开指定网址
    */
    function OpenUrl($url) {
        #重设各参数
        $this->m_url = "";
        $this->m_urlpath = "";
        $this->m_scheme = "http";
        $this->m_host = "";
        $this->m_port = "80";
        $this->m_user = "";
        $this->m_pass = "";
        $this->m_path = "/";
        $this->m_query = "";
        $this->m_error = "";
        $this->m_httphead = "" ;
        $this->m_html = "";
        $this->Close();
        #初始化系统
        $this->PrivateInit($url);
        $this->PrivateStartSession();
    }
 
    /**
    * 获得某操作错误的原因
    */
    public function printError() {
        echo "错误信息：".$this->m_error;
        echo "具体返回头：<br>";
        foreach($this->m_httphead as $k=>$v) {
            echo "$k => $v <br>\r\n";
        }
    }
  
    /**
    * 判别用Get方法发送的头的应答结果是否正确
    */
    public function IsGetOK() {
        if( ereg("^2",$this->GetHead("http-state")) ) {
            return true;
        } else {
            $this->m_error .= $this->GetHead("http-state")." - ".$this->GetHead("http-describe")."<br>";
            return false;
        }
    }
     
    /**
    * 看看返回的网页是否是text类型
    */
    public function IsText() {
        if (ereg("^2",$this->GetHead("http-state")) && eregi("^text",$this->GetHead("content-type"))) {
            return true;
        } else {
            $this->m_error .= "内容为非文本类型<br>";
            return false;
        }
    }
    /**
    * 判断返回的网页是否是特定的类型
    */
    public function IsContentType($ctype) {
        if (ereg("^2",$this->GetHead("http-state")) && $this->GetHead("content-type") == strtolower($ctype)) {
            return true;
        } else {
            $this->m_error .= "类型不对 ".$this->GetHead("content-type")."<br>";
            return false;
        }
    }
     
    /**
    * 用 HTTP 协议下载文件
    */
    public function SaveToBin($savefilename) {
        if (!$this->IsGetOK()) return false;
        if (@feof($this->m_fp)) {
            $this->m_error = "连接已经关闭！";
            return false;
        }
        $fp = fopen($savefilename,"w") or die("写入文件 $savefilename 失败！");
        while (!feof($this->m_fp)) {
            @fwrite($fp,fgets($this->m_fp,9999));
        }
        @fclose($this->m_fp);
        return true;
    }
     
    /**
    * 保存网页内容为 Text 文件
    */
    public function SaveToText($savefilename) {
        if ($this->IsText()) {
            $this->SaveBinFile($savefilename);
        } else {
            return "";
        }
    }
     
    /**
    * 用 HTTP 协议获得一个网页的内容
    */
    public function GetHtml() {
        if (!$this->IsText()) return "";
        if ($this->m_html!="") return $this->m_html;
        if (!$this->m_fp||@feof($this->m_fp)) return "";
        while(!feof($this->m_fp)) {
            $this->m_html .= fgets($this->m_fp,9999);
        }
        @fclose($this->m_fp);
        return $this->m_html;
    }
     
    /**
    * 开始 HTTP 会话
    */
    public function PrivateStartSession() {
        if (!$this->PrivateOpenHost()) {
            $this->m_error .= "打开远程主机出错!";
            return false;
        }
        if ($this->GetHead("http-edition")=="HTTP/1.1") {
            $httpv = "HTTP/1.1";
        } else {
            $httpv = "HTTP/1.0";
        }
        fputs($this->m_fp,"GET ".$this->m_urlpath." $httpv\r\n");
        fputs($this->m_fp,"Host: ".$this->m_host."\r\n");
        fputs($this->m_fp,"Accept: */*\r\n");
        fputs($this->m_fp,"User-Agent: Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.2)\r\n");
        #HTTP1.1协议必须指定文档结束后关闭链接,否则读取文档时无法使用feof判断结束
        if ($httpv=="HTTP/1.1") {
            fputs($this->m_fp,"Connection: Close\r\n\r\n");
        } else {
            fputs($this->m_fp,"\r\n");
        }
        $httpstas = fgets($this->m_fp,9999);
        $httpstas = split(" ",$httpstas);
        $this->m_httphead["http-edition"] = trim($httpstas[0]);
        $this->m_httphead["http-state"] = trim($httpstas[1]);
        $this->m_httphead["http-describe"] = "";
        for ($i=2;$i<count($httpstas);$i++) {
            $this->m_httphead["http-describe"] .= " ".trim($httpstas[$i]);
        }
        while (!feof($this->m_fp)) {
            $line = str_replace("\"","",trim(fgets($this->m_fp,9999)));
            if($line == "") break;
            if (ereg(":",$line)) {
                $lines = split(":",$line);
                $this->m_httphead[strtolower(trim($lines[0]))] = trim($lines[1]);
            }
        }
    }
     
    /**
    * 获得一个Http头的值
    */
    public function GetHead($headname) {
        $headname = strtolower($headname);
        if (isset($this->m_httphead[$headname])) {
            return $this->m_httphead[$headname];
        } else {
            return "";
        }
    }
     
    /**
    * 打开连接
    */
    public function PrivateOpenHost() {
        if ($this->m_host=="") return false;
        $this->m_fp = @fsockopen($this->m_host, $this->m_port, &$errno, &$errstr,10);
        if (!$this->m_fp){
            $this->m_error = $errstr;
            return false;
        } else {
            return true;
        }
    }
     
    /**
    * 关闭连接
    */
    public function Close(){
        @fclose($this->m_fp);
    }
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
