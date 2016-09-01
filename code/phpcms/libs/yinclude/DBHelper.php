<?php
/**
 * Copyright (c) 2010, 新浪网研发综合开发
 * All rights reserved.
 *
 * 文件名称：DBHelper.php
 * 摘    要：仅适用于动态平台的快速数据库操作类
 * 作    者：杨振宇
 * 版    本：0.3
 * 2010-01-29  增加执行写操作，直接返回的影响行数的
 */
class DBHelper{
	private $conn ;
	private $debug ;
	private $debug_data;
	private $connector;
	private $server_conf = array();
	private $fetch_array_type = MYSQL_ASSOC;
	
	/**
	* @name 构造函数
	* @param $write_debug 开启写调试
	* @param $read_debug  开启读调试
	* @param $server_conf array 该参数也可以作为第一参数传入
	*    单一用户
	*      host 数据库地址
	*      port 端口
	*      user 用户名
	*      pass 密码
	*      name 数据库
	*
	*    读写分离
	*      read array
	*          host 数据库地址
	*          port 端口
	*          user 用户名
	*          pass 密码
	*          name 数据库
    *      write array
    *          ......	
	*/
	function DBHelper($write_debug=FALSE,$read_debug=FALSE,$server_conf=FALSE){
		$this->connector = array();
		$this->debug_data = array();
		
		if(is_array($write_debug)){
			$server_conf = $write_debug ;
			$write_debug = false;
		}
		
		if(is_array($read_debug)){
			$server_conf = $read_debug ;
			$read_debug = false;
		}
		
		//数据库服务器信息
		if(($server_conf && is_array($server_conf))){
			if(!isset($server_conf['read'])){
				$this->connector['read'] = &$this->connector['write'] ;
				$server_conf['read'] = $server_conf ;
				$server_conf['write'] = $server_conf['read'];
			}
			if(!isset($server_conf['read']) || !isset($server_conf['write'])) die('db server conf error');	
		}else{
			if(!defined('DB_CONFIG')) die('DB CONFIG error');
			//localhost;3306;user;pass;dbname
			
			$tmp = explode(';',DB_CONFIG) ;
			$server_conf = array();
			$server_conf['write'] = array(
				'host' => $tmp[0],
				'port' => $tmp[1],
				'user' => $tmp[2],
				'pass' => $tmp[3],
				'name' => $tmp[4]
			);
			if(defined('DB_R_CONFIG')){
				$tmp = explode(';',DB_R_CONFIG) ;
				$server_conf['read'] = array(
					'host' => $tmp[0],
					'port' => $tmp[1],
					'user' => $tmp[2],
					'pass' => $tmp[3],
					'name' => $tmp[4]
				);
			}else{
				$server_conf['read'] = $server_conf['write'] ;
				$this->connector['read'] = &$this->connector['write'] ;
			}
			unset($tmp);
		}
		
		$this->server_conf = $server_conf;
		
		$this->debug = array();
		$this->debug['write'] = $write_debug ;
		$this->debug['read'] = $read_debug ;
	}
	
	/**
	* @name 析构函数 释放数据库链接,如果开启调试则输出调试 
	* @note 调试在FF3.5效果会更好
	*/
	function __destruct(){
		if(count($this->debug) > 0 && ($this->debug['write'] || $this->debug['read'] )){
			$debug_types = array('read','write');
			$html = '';
			$has_data = FALSE;
			foreach($debug_types as $debug_type){
				if(isset($this->debug_data[$debug_type]) && count($this->debug_data[$debug_type]) > 0){
					$has_data = TRUE;
					$html .= '<div style="margin:10px;padding:3px;border:1px #ccc solid;font-size:9pt;font-family:Arial;">';
					foreach($this->debug_data[$debug_type] as $sql){
						if(!is_array($sql)) $sql = array('SQL'=>$sql);
						$html .= '<div style="border-bottom:1px #ccc dashed;">';
						foreach($sql as $k=>$v){
							if($k != 'EXPLAIN')	$v = htmlspecialchars($v);
							$html .= "<div><span style='color:blue;'>$k</span>:<span style='color:red;'>$v</span></div>";
						}
						$html .= '</div>';
					}
					$html .= '</div>';
				}
			}
			$html .='</div>';
			if($has_data)	print '<div style="bottom:0;right:0; height:150px;text-align:left;overflow:auto;z-index:999999;position:fixed;word-wrap:break-word;word-break:break-all ;width:100%;background:#fff;"><div style="text-align:center"><a href="javascript:;" onclick="this.parentNode.parentNode.style.display=\'none\';return false;">close</a></div>'.$html ;
		}
		$this->closeAll();
	}
	
	private function closeAll(){
		foreach ($this->connector as $connIDX => $v)
			if($v){
				@mysql_close($this->connector[$connIDX]);
				unset($this->connector[$connIDX]);
			}
	}

	private function connect($db_server, $db_port='3306',$db_user, $db_pass, $db_name,  $connIDX) {
		$this->connector[$connIDX] = mysql_connect($db_server . ':' . $db_port, $db_user, $db_pass,true);
		mysql_select_db($db_name, $this->connector[$connIDX]);
		mysql_query("SET NAMES 'UTF8'");
	}

	private function connectDBR(){
		extract($this->server_conf['read']);
		$this->connect($host,$port,$user,$pass,$name,'read');
	}

	private function connectDBW(){
		extract($this->server_conf['write']);
		$this->connect($host,$port,$user,$pass,$name,'write');
	}
	
	private function getMixSqlStr($format,$k,$v){
		if(is_string($v)){
			$v = "'#s'" ;
		}else if(is_int($v)){
			$v = '#d' ;
		}else{
			$v = "'#s'" ;
		}
		$format = str_replace('{v}',$v,$format);
		$format = str_replace('{k}',"`$k`",$format);
		return $format ;
	}
	
	private function mixSql($sql,$data){
		if(strpos($sql,'#') === FALSE) return $sql ;
		if(is_array($data) && count($data)>0){
			$this->queryCallback($data, TRUE);
			$sql = preg_replace_callback('/(#d|#s|##|#f|#b|#n)/', array('DBHelper','queryCallback'), $sql);
		}
		return $sql ;
	}
	
	static function queryCallback($match, $init=FALSE){
		static $args = NULL;
		if ($init) {
			$args = $match;
			return ;
		}
		switch ($match[1]) {
			case '#d':
				return (int) array_shift($args);
			case '#s':
				return mysql_real_escape_string(array_shift($args));
			case '#n':
				$value = trim(array_shift($args));
				return is_numeric($value) && !preg_match('/x/i', $value) ? $value : '0';
			case '##':
				return '#';
			case '#f':
				return (float) array_shift($args);
			case '#b':
				return mysql_real_escape_string(array_shift($args));
		}
	}
	
	/**
	* @name 设置数据库读时候返回的数组类型
	* @param $type [MYSQL_ASSOC | MYSQL_NUM | MYSQL_BOTH]
	*/
	public function setFetchArrayType($type){
		if($type != MYSQL_ASSOC || $type != MYSQL_NUM || $type != MYSQL_BOTH ) return ;
		$this->fetch_array_type = $type ;
	}
	
	/**
	* @name 设置调试
	* @param $key   ['read' | 'write']
	* @param $value [TRUE | FALSE]
	*/
	public function setDebug($key,$value){
		if(!in_array($key,array('read','write'))) return FALSE ;
		if($value !== TRUE) $value = FALSE;
		$this->debug[$key] = $value ;
	}
	
	/**
	* @name 返回从数据库第一行数据
	* @param  $table  string
	* @param  $where  mix string|array
	* @param  $fields string
	* @param  $tail mix int|string|array
	* @return array
	* @see r()
	*/
	public function r1($table,$where='',$fields='*',$tail=array()){
		$tail['limit'] = '0,1';
		$re = $this->r($table,$where,$fields,$tail,1) ; 
		if(count($re) > 0)	return $re[0];
		return array();
	}
	
	/**
	* @name 读数据库
	* @param  $table  string
	*    表名称
	* @param  $where  mix string|array
	*    条件
	*    string : 'where id=1'
	*    array  : array('id'=>1,'type'=>'blog') == "where `id`=1 and `type`='blog'" 
	* @param  $fields string
	*    字段
	*    string : '*' | 'id,name' | 'count(*) as c'
	* @param  $tail mix int|string|array
	* @return array
	*    返回的结果,如果错误或者没有结果集均返回 空数组
	* @see readBySql()
	*/
	public function r($table, $where='', $fields='*', $tail=array()){
		$v_arr = FALSE ;
		if(is_array($where)){
			if(count($where) > 0){
				$where_arr = array();
				$v_arr = array();
				foreach($where as $k=>$v){
					if(is_array($v)){
						$tmp = array() ;
						foreach($v as $iv){
							$tmp[] = $this->getMixSqlStr('{v}','',$iv) ;
							$v_arr[] = $iv ;
						}
						$tmp = join(',',$tmp) ;
						$where_arr[] = $this->getMixSqlStr('{k}',$k,'')." in ($tmp)" ;
					}else{
						$where_arr[] = $this->getMixSqlStr('{k}={v}',$k,$v) ;
						$v_arr[] = $v ;
					}
				}
				$where = 'where '.join(' and ',$where_arr);
			}else{
				$where = '';
			}
		}
		return $this->rs("select $fields from $table $where",$v_arr,$tail) ;
	}
	
	/**
	* @name 写数据库
	* @param  $table  string
	*    表名称
	* @param  $data   array
	*    要写入的数据
	*    array  : array('title'=>'blog','typeid'=>5) 
	* @param  $unique mix bool | string | array
	*    写入条件
	*    1.不传入则为新增 
	*        $db_helper->write('blog',array('title'=>'a blog','created'=>time()));
	*    2.传入字符串 应为唯一主键,并且在$data参数中存在该值
	*        $db_helper->write('blog',array('title'=>'this is a blog','changed'=>time(),'id'=>1),'id');
	*    3.传入数组
	*         $db_helper->write('blog',array('title'=>'this is a blog','changed'=>time()),array('id'=>1));
	* @return 
	*    新增 返回 last_insert_id
	*    更新 返回 更新结果
	* @see excute()
	*/
	public function w($table,$data,$unique=FALSE,$method='insert'){
		if(!is_array($data) || count($data) < 1) return FALSE ;
		$k_arr = array() ;
		$v_arr = array() ;
		if(empty($unique)){
			foreach($data as $k=>$v){
				$k_arr[] = $k ;
				$v_arr[] = $this->getMixSqlStr('{v}',$k,$v) ;
			}
			$data = array_values($data);
			$sql = "$method into `$table` (".join(',',$k_arr).') values ('.join(',',$v_arr).')';
			$result = $this->e($sql,$data);
			if($result){
				$isert_id = mysql_insert_id($this->connector['write']);
				if($isert_id) return $isert_id ;
				return $result ;
			}
			return false;
		}
		foreach($data as $k=>$v){
			$k_arr[] = $this->getMixSqlStr('{k}={v}',$k,$v) ;
		}
		
		if(is_string($unique) && isset($data[$unique])){
			$unique = array($unique=>$data[$unique]);
		}
		
		$data = array_values($data);
		
		$where_sql = '';
		if(is_array($unique)){
			$where_arr = array();
			foreach($unique as $k=>$v){
				$where_arr[] = $this->getMixSqlStr('{k}={v}',$k,$v) ;
				$data[] = $v ;
			}
			$where_sql = 'where '.join(' and ',$where_arr);
		}else if(is_string($unique)){
			$where_sql = $unique ;
		}else
			return FALSE;
		$sql = "update `$table` set ".join(',',$k_arr).' '.$where_sql;
		return $this->e($sql,$data);
	}
	
	/**
	* @name 执行SQL语句(打开写数据库)
	* @param  $sql  string
	*    SQL 语句
	*        update blog set title = 'new title' where id=1;
	*    可将SQL语句中的参数替换
	*        update blog set title = '#s' where id=#d
	*        #s 字符串
	*        #d int
	*        #n number
	*        #f float
	*        ## #
	* @param  $data  array() 数字索引数组
	*    做为参数传入的数据
	*        array('new title',1);
	* @return 执行结果
	* @see readBySql()
	*/
	public function e($sql,$data=FALSE){
		if(!isset($this->connector['write'])) $this->connectDBW();
		$start_time= $this->debug['write'] ? microtime(true) : 0;
		if($data != FALSE)	$sql = $this->mixSql($sql,$data);
		$result = mysql_query($sql, $this->connector['write']) ;
		if($this->debug['write']){
			$arr = array();
			$arr['SQL'] = $sql ;
			$arr['AFFECTED ROWS'] = mysql_affected_rows($this->connector['write']);
			$arr['RESULT'] = $result ? 'true' : 'false:-'.mysql_error($this->connector['write']) ;
			$arr['TIME'] = microtime(true)-$start_time;
			$this->debug_data['write'][] = $arr ;
		}
		if($result){
			return mysql_affected_rows($this->connector['write']);
		}
		return $result ;
	}
	
	/**
	* @name 执行SQL语句(打开写数据库)
	* @param  $sql  string
	*    SQL 语句
	*        select * from blog where id=1;
	*    可将SQL语句中的参数替换
	*        select * from blog where title like '%#s%';
	* @param  $data  array() 数字索引数组
	*    做为参数传入的数据
	*        array('new');
	* @param $tail array
	*    array array('expire'=>30,'sql'=>'order by id','limit'=>'0,10','m_key'=>'m_key') 同时设置sql尾巴及缓存时间
	* @return array
	*    根据设置的数据库读时候返回的数组类型 返回数组
	* @see excute()
	*/
	public function rs($sql,$data=array(),$tail=array()){
		if(!isset($this->connector['read'])) $this->connectDBR();
		$start_time= $this->debug['write'] ? microtime(true) : 0;
		
		//增加SQL尾巴
		if(isset($tail['sql'])) $sql .= " {$tail['sql']}";
		if(isset($tail['limit'])) $sql .= " limit {$tail['limit']}";
		
		if($data != FALSE)	$sql = $this->mixSql($sql,$data);
		
		//这里加入缓存策略
		if(!empty($tail['expire'])){
			if(!isset($tail['m_key'])){
				$tail['m_key'] = md5($sql);
			}
			$rows = $this->getM($tail['m_key']);
			if($rows){
				if($this->debug['read'] && empty($tail['no_debug'])){
					$arr = array();
					$arr['SQL'] = $sql ;
					$arr['FROM_CACHE'] = 'true' ;
					$arr['M_KEY'] = $tail['m_key'] ;
					$arr['TIME'] = microtime(TRUE)-$start_time;
					$this->debug_data['read'][] = $arr ;
				}
				return $rows ;
			}
		}
		$rs = mysql_query($sql, $this->connector['read']);
		if($rs){
			$rows = array();
			while($row = mysql_fetch_array($rs,$this->fetch_array_type)){
				if(isset($tail['r_key']) && $tail['r_key'] != '' && isset($row[$tail['r_key']])){
					$rows[$row[$tail['r_key']]] = $row ;
				}else if(isset($tail['func_row'])){
					$tmp = $tail['func_row']($row) ;
					if($tmp == 'break'){
						break ;
					}
					if($tmp) $rows[] = $tmp ;
					unset($tmp) ;
				}else if(isset($tail['key_value']) && strpos($tail['key_value'],',')){
					$tmp = explode(',',$tail['key_value']) ;
					if(!isset($row[$tmp[0]]) || !isset($row[$tmp[1]])) die('db:key_value error!') ;
					$rows[$row[$tmp[0]]] = $row[$tmp[1]] ;
				}else if(!empty($tail['r_group_key'])){
					if(!isset($tail['r_group_keyfix'])) $tail['r_group_keyfix'] = '' ;
					$rows[$tail['r_group_keyfix'].$row[$tail['r_group_key']]][] = $row ;
				}else{
					$rows[] = $row;
				}
			}
			//这里加入缓存策略
			if(!empty($tail['expire'])){
				$this->setM($tail['m_key'], $rows,$tail['expire']);
			}
			if($this->debug['read'] && empty($tail['no_debug'])){
				$arr = array();
				$arr['SQL'] = $sql ;
				$arr['ROWS'] = mysql_num_rows($rs);
				$arr['TIME'] = microtime(TRUE)-$start_time;
				if(!empty($tail['expire'])){
					$arr['CACHED'] = 'TRUE';
					$arr['M_KEY'] = $tail['m_key'] ;
				}
				$_drs = $this->rs('explain '.$sql,false,array('no_debug'=>true)) ;
				$_drs_html = '<style> #d_bug_explain { width:100%;border-collapse:collapse;} #d_bug_explain td {border:1px solid #99D3FB;}</style><table cellspacing="0" id="d_bug_explain">' ;
				foreach($_drs as $k=>$vs){
					if($k == '0') $_drs_html_1 = '<tr style="color:blue;">' ;
					else $_drs_html_1 = '' ;
					$tmp = '<tr style="color:red;">' ;
					foreach($vs as $ik=>$iv){
						if($k == '0') $_drs_html_1 .= '<td>'.$ik.'</td>' ;
						$tmp .= '<td>'.$iv.'</td>' ; 
					}
					if($k == '0') $_drs_html_1 .= '</tr>' ;
					$_drs_html .= $_drs_html_1 . $tmp .  '</tr>' ;
				}
				
				$arr['EXPLAIN'] = $_drs_html . '</table><br />' ;
				$this->debug_data['read'][] = $arr ;
			}
			return $rows;
		}
		if($this->debug['read']){
			$arr = array();
			$arr['SQL'] = $sql ;
			$arr['RESULT'] = 'false:-'. mysql_error($this->connector['read']);
			$arr['TIME'] = microtime(TRUE)-$start_time;
			$this->debug_data['read'][] = $arr ;
		}
		
		return array();
	}
	
	/**
	* @name 删除数据
	* @param  $table  string
	*    表名称
	* @param  $where  mix string|array
	*    条件
	*    string : 'where id=1'
	*    array  : array('id'=>1,'type'=>'blog') == "where `id`=1 and `type`='blog'" 
	* @return 执行结果
	* @see read()
	*/
	public function d($table,$where){
		$v_arr = FALSE ;
		if(is_array($where)){
			$where_arr = array();
			$v_arr = array();
			foreach($where as $k=>$v){
				$where_arr[] = $this->getMixSqlStr('{k}={v}',$k,$v) ;
				$v_arr[] = $v ;
			}
			$where = 'where '.join(' and ',$where_arr);
		}
		$sql = "delete from `$table` $where";
		return $this->e($sql,$v_arr) ;
	}

	/**
	* @name 获得SQL缓存数据
	* @param $m_key string
	*/
	public function getM($m_key){
		include_once 'f_cache.php' ;
		return f_get_data('db-'.$m_key);
	}

	/**
	* @name 设置SQL缓存数据
	* @param $m_key string
	* @param $m_value mix
	* @param $ttl int
	*/
	public function setM($m_key,$m_value,$ttl){
		include_once 'f_cache.php' ;
		f_set_data('db-'.$m_key,$m_value,$ttl);
	}

	/**
	* @name 删除SQL缓存
	* @param $m_key string
	*/
	public function delM($m_key){
		include_once 'f_cache.php' ;
		f_del_data('db-'.$m_key);
	}

}
