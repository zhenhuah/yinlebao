<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 

define('TASK_PATH',APP_PATH.'statics/task/');//任务路径

class view {
	function __construct() {

		$this->task_db = pc_base::load_model('cms_pre_task_model');			//任务信息表连接
		$this->task_video_db = pc_base::load_model('cms_pre_task_video_model');//视频任务数据表连接
		$this->term_db = pc_base::load_model('cms_term_type_model');	//终端类型信息表连接		
		//$this->posid_db = pc_base::load_model('cms_position_model');	//推荐位信息表连接
		 
		//所有终端类型 v9_term_type
		$get_term_data = $this->term_db->select();
		if(!empty($get_term_data))	//过滤归整一下方便后面直接使用
		{
			foreach($get_term_data as $key => $row)
			{
				$temp_term_list[$row['id']] = $row;
			}

			$this->term_list = $temp_term_list;
		}
	}
	
	public function init() {

		$term_id = trim($_GET['term_id']);		//终端类型
		$taskDate = trim($_GET['taskDate']);	//日期
		$taskDate = strtotime($taskDate);
		$spid = trim($_GET['spid']);	//运营商ID

		if(!empty($term_id) && is_numeric($term_id) && !empty($spid))
		{
			if(empty($taskDate) || !is_numeric($taskDate))
			{
				showmessage('请选择一个日期才可以浏览');
			}

			switch($term_id)
			{
				case '1':	//STB
					self::stb($taskDate,$spid);
				break;

				case '2':	//PAD
					self::pad($taskDate,$spid);
				break;

				case '3':	//PHONE
					self::phone($taskDate,$spid);
				break;

				case '4':	//PC
					exit('功能未开通');
				break;

				default:
					showmessage('操作错误或终端机类型不存在!');
				break;
			}
		}else{
			showmessage('操作错误或终端及运营商不存在');
		}
	
	}
	
	/*
	* STB  首页
	*/
	private function stb($taskDate,$spid) {
		if(empty($taskDate))
		{
			showmessage('操作错误或数据不存在!');
		}

		/*
		* 各个运营商的推荐位配置 STB 终端类型
		* 这里要使用一个文件加载进来(暂没搞清楚如果加载，后面优化)
		* 有多少个运营商就得把数据库里生成的ID，对应的配置一下，否则程序不知道那个ID是干什么的
		* 只更换运营商ID和对应的推荐位ID
		*/
		$config_spid_data = array(
			'ddssp' => array(
					'1' => 'posidOne',		//首页滚动
					'2' => 'posidTwo',		//文字区
					'3' => 'posidThree',	//今日更新
					'4' => 'posidFour',		//电视栏目
					'5' => 'posidFive',		//电影
					'6' => 'posidSix',		//电视剧
					'7' => 'posidSeven',	//乐酷
				),
		);
		if(!in_array($spid,array_keys($config_spid_data)))
		{
			showmessage('当前操作所属运营商没有配置,请联系技术配置!');
		}
		$aKey .= " spid = '".$spid."' AND taskStatus > '0' AND term_id = '1' AND taskDate = '".$taskDate."'";

		$get_config_spid_data = $config_spid_data[$spid];
		
		$taskParkAll = $this->task_db->listinfo($aKey);
		if(!empty($taskParkAll))
		{			
			foreach($taskParkAll as $key => $value)
			{
				$posid = $value['posid'];
				$get_config_spid_data[$posid];

				$findTask = array(
					'taskId' => $value['taskId'],
					'posid' => $posid
					);
				$videoTask[$get_config_spid_data[$posid]] = $this->task_video_db->listinfo($findTask,$order = '`videoSort` ASC');
			}
		}
		include template('task','stb');
	}

	/*
	* IPAD  首页
	*/
	private function pad($taskDate,$spid) {
		if(empty($taskDate))
		{
			showmessage('操作错误或数据不存在!');
		}

		/*
		* 各个运营商的推荐位配置 IPAD 终端类型
		* 这里要使用一个文件加载进来(暂没搞清楚如果加载，后面优化)
		* 有多少个运营商就得把数据库里生成的ID，对应的配置一下，否则程序不知道那个ID是干什么的
		* 只更换运营商ID和对应的推荐位ID
		*/
		$config_spid_data = array(
			'ddssp' => array(
					'8' => 'posidOne',		//首页滚动
					'9' => 'posidTwo',		//文字区
					'10' => 'posidThree',	//今日更新
					'11' => 'posidFour',	//电视栏目
					'12' => 'posidFive',	//电影
					'13' => 'posidSix',		//电视剧
					'14' => 'posidSeven',	//乐酷
					'36' => 'posidAdOne',	//今日更新跑马灯
					'37' => 'posidAdTwo',	//电视栏目跑马灯
				),
		);
		if(!in_array($spid,array_keys($config_spid_data)))
		{
			showmessage('当前操作所属运营商没有配置,请联系技术配置!');
		}
		$aKey .= " spid = '".$spid."' AND taskStatus > '0' AND term_id = '2' AND taskDate = '".$taskDate."'";
		$get_config_spid_data = $config_spid_data[$spid];
		
		$taskParkAll = $this->task_db->listinfo($aKey);
		if(!empty($taskParkAll))
		{
			foreach($taskParkAll as $key => $value)
			{
				$posid = $value['posid'];
				$get_config_spid_data[$posid];

				$findTask = array(
					'taskId' => $value['taskId'],
					'posid' => $posid
					);
				$videoTask[$get_config_spid_data[$posid]] = $this->task_video_db->listinfo($findTask,$order = '`videoSort` ASC');
			}
		}

		//幻灯片效果处理 start
		
		if(!empty($videoTask['posidOne']))
		{
			$totalNums = count($videoTask['posidOne']);	//一共多少条数据
			$pageNums = @ceil($totalNums/3);	//一共多少页
	
			foreach($videoTask['posidOne'] as $key => $row)
			{
				//最多5个组15条数据
				if($key < 3){	//第一页
					$posidOne_data[1][] = $row;
				}else if (($key >= 3) && ($key < 6)){	//第二页
					$posidOne_data[2][] = $row;
				}else if (($key >= 6) && ($key < 9)){	//第三页
					$posidOne_data[3][] = $row;
				}else if (($key >= 9) && ($key < 12)){	//第四页
					$posidOne_data[4][] = $row;
				}else if (($key >= 12) && ($key < 15)){	//第五页
					$posidOne_data[5][] = $row;
				}
			}
		}
		//print_r($posidOne_data[2]);
		//幻灯片效果处理 end 
		include template('task','ipad');
	}


	/*
	* PHONE  首页
	*/
	private function phone($taskDate,$spid) {		
		if(empty($taskDate))
		{
			showmessage('操作错误或数据不存在!');
		}

		/*
		* 各个运营商的推荐位配置 PHONE 终端类型
		* 这里要使用一个文件加载进来(暂没搞清楚如果加载，后面优化)
		* 有多少个运营商就得把数据库里生成的ID，对应的配置一下，否则程序不知道那个ID是干什么的
		* 只更换运营商ID和对应的推荐位ID
		*/
		$config_spid_data = array(
			'ddssp' => array(
					'15' => 'posidOne',		//首页滚动
					'16' => 'posidTwo',		//文字区
					'17' => 'posidThree',	//今日更新
					'18' => 'posidFour',	//电视栏目
					'19' => 'posidFive',	//电影
					'20' => 'posidSix',		//电视剧
					'21' => 'posidSeven',	//乐酷
					'38' => 'posidAdOne',	//今日更新跑马灯
				),
		);
		if(!in_array($spid,array_keys($config_spid_data)))
		{
			showmessage('当前操作所属运营商没有配置,请联系技术配置!');
		}
		$aKey .= " spid = '".$spid."' AND taskStatus > '0' AND term_id = '3' AND taskDate = '".$taskDate."'";
		$get_config_spid_data = $config_spid_data[$spid];
		
		$taskParkAll = $this->task_db->listinfo($aKey);
		if(!empty($taskParkAll))
		{
			foreach($taskParkAll as $key => $value)
			{
				$posid = $value['posid'];
				$get_config_spid_data[$posid];

				$findTask = array(
					'taskId' => $value['taskId'],
					'posid' => $posid
					);
				$videoTask[$get_config_spid_data[$posid]] = $this->task_video_db->listinfo($findTask,$order = '`videoSort` ASC');
			}
		}
		
		include template('task','phone');	
	}

}//end class
