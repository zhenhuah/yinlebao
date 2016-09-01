<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class import extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		dbmover_css();
	}   	
	
	public function init() {
		//dbmover_import_link();
		echo '操作成功';
	}
	
	/**
	 * 导入直播媒体
	 */
	public function  channel() {
		
		$this->go3c_channel = pc_base::load_model('go3c_channel_model');
		$this->cms_channel  = pc_base::load_model('cms_channel_model');
		$this->cms_channel_data  = pc_base::load_model('cms_channel_data_model');
		
		$infos = $this->go3c_channel->select('','*','',$order = 'id ASC', $group = 'id');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			
			//以ChannelID为标准进行判断
			$data_target = $this->cms_channel->get_one(array('channel_id'=>$value['id']), 'channel_id, title');
			
			if($data_target['channel_id'] == ''){
				$data_array = array(
					'channel_id' 	   => $value['id'],
					'title' 	 	   => $value['text'],
					'img' 	  	  	   => $value['img'],
					'imgpath' 	  	   => $value['imgpath'],
					'channel_category' => $value['channel_category'],
					'iswidth' 	  	   => $value['iswidth'],
					'uuidPC_HIGH' 	   => get_live_url('PC_HIGH', $value['uuidPC_HIGH']),
					'uuidIOS_HIGH'     => get_live_url('IOS_HIGH',$value['uuidIOS_HIGH']),
					'uuidSTB_HIGH' 	   => get_live_url('STB_HIGH',$value['uuidSTB_HIGH']),
					'uuidPC_MEDIUM'    => get_live_url('PC_MEDIUM',$value['uuidPC_MEDIUM']),
					'uuidIOS_MEDUIM'   => get_live_url('IOS_MEDUIM',$value['uuidIOS_MEDUIM']),
					'uuidSTB_MEDIUM'   => get_live_url('STB_MEDIUM',$value['uuidSTB_MEDIUM']),
					'catid' 	  	   => '63',
					'status'     	   => '99',
					'sysadd'      	   => '1',
					'username'    	   => 'system',
					'inputtime'   	   => time(),
					'updatetime'  	   => time(),
				);
				$data_op = '<span style="color:red;">插入</span>';
				$insert_id = $this->cms_channel->insert($data_array, true);
				$this->cms_channel_data->insert(array('id' => $insert_id));
			}else{
				$data_array = array(
					'title' 	 	   => $value['text'],
					'img' 	  	  	   => $value['img'],
					'imgpath' 	  	   => $value['imgpath'],
					'iswidth' 	  	   => $value['iswidth'],
					'uuidPC_HIGH' 	   => get_live_url('PC', $value['uuidPC_HIGH']),
					'uuidIOS_HIGH'     => get_live_url('IOS',$value['uuidIOS_HIGH']),
					'uuidSTB_HIGH' 	   => get_live_url('STB',$value['uuidSTB_HIGH']),
					'uuidPC_MEDIUM'    => get_live_url('PC',$value['uuidPC_MEDIUM']),
					'uuidIOS_MEDUIM'   => get_live_url('IOS',$value['uuidIOS_MEDUIM']),
					'uuidSTB_MEDIUM'   => get_live_url('STB',$value['uuidSTB_MEDIUM']),
					'updatetime'  	   => time()
				);
				$data_op = '<span style="color:green;">更新</span>';
				$this->cms_channel->update($data_array, array('channel_id'=>$value['id']));
			}
			echo $data_op.' : '.$value['text'].' '.$value['img_path'].'<br />';
			//echo '<pre>'.$value['id'];print_r($data_array);exit;
		}
		echo '</div>';
		showmessage(L('operation_success'),'?m=dbmover&c=import&a=init',$ms = 1250);
	}
	
	/**
	 * 导入EPG
	 */
	public function  program() {
		
		$this->go3c_program   		  = pc_base::load_model('go3c_program_model');
		$this->cms_channel			  = pc_base::load_model('cms_channel_model');
		$this->cms_channelepg 		  = pc_base::load_model('cms_channelepg_model');
		$this->cms_channelepg_data 	  = pc_base::load_model('cms_channelepg_data_model');

		//计算总数和分页页数
		$field    	= '*';
		$sql     	= 'go3c_program WHERE 1 '; 
		$order  	= '';
		$perpage 	= 1;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->go3c_program->mynum($sql);
		$totalpage	= $this->go3c_program->mytotalpage($sql, $perpage);
		
		//如果不大于最多页数查询并开始执行数据导入
		if($page < $totalpage+1){
			
			//获取数据
			$data_array = $this->go3c_program->listinfo('', $order, $page, $perpage);	
			
			//显示分页情况
			echo $this->go3c_program->pages;			
			
			//循环处理数据并导入
			echo '<div id="syn_msg">';			
			foreach ($data_array as $value) {
				$data_target = $this->cms_channelepg->get_one(array('epgid'=>$value['id']), '*');
				$channel = $this->cms_channel->get_one(array('channel_id'=>$value['channel_id']), 'channel_id, title');
				if(!$data_target['epgid']){
					$data_array = array(
						'title'   	  => $channel['title'].' '.$value['systemdate'].' '.$value['text'], 
						'epgid'  	  => $value['id'], 
						'channel_id'  => $value['channel_id'], 
						'systemdate'  => $value['systemdate'], 
						'text'   	  => $value['text'], 
						'starttime'   => $value['starttime'], 
						'endtime'     => $value['endtime'], 
						'uuidPC_HIGH' 	   => get_timeshift_url('PC', $value['uuidPC_HIGH']),
						'uuidIOS_HIGH'     => get_timeshift_url('IOS',$value['uuidIOS_HIGH']),
						'uuidSTB_HIGH' 	   => get_timeshift_url('STB',$value['uuidSTB_HIGH']),
						'uuidPC_MEDIUM'    => get_timeshift_url('PC',$value['uuidPC_MEDIUM']),
						'uuidIOS_MEDUIM'   => get_timeshift_url('IOS',$value['uuidIOS_MEDUIM']),
						'uuidSTB_MEDIUM'   => get_timeshift_url('STB',$value['uuidSTB_MEDIUM']),
						'catid'   	  => '9', 
						'status'      => '99',
						'sysadd'      => '1',
						'username'    => 'system',
						'inputtime'   => time(),
						'updatetime'  => time(),
					);
					$insert_id = $this->cms_channelepg->insert($data_array, true);
					$this->cms_channelepg_data->insert(array('id' => $insert_id));
					$data_op = '<span style="color:red;">插入</span>';
				}else{
					$data_array = array(
						'title'   	  => $channel['title'].' '.$value['systemdate'].' '.$value['text'], 
						'channel_id'  => $value['channel_id'], 
						'systemdate'  => $value['systemdate'], 
						'text'   	  => $value['text'], 
						'starttime'   => $value['starttime'], 
						'endtime'     => $value['endtime'], 
						'uuidPC_HIGH' 	   => get_timeshift_url('PC', $value['uuidPC_HIGH']),
						'uuidIOS_HIGH'     => get_timeshift_url('IOS',$value['uuidIOS_HIGH']),
						'uuidSTB_HIGH' 	   => get_timeshift_url('STB',$value['uuidSTB_HIGH']),
						'uuidPC_MEDIUM'    => get_timeshift_url('PC',$value['uuidPC_MEDIUM']),
						'uuidIOS_MEDUIM'   => get_timeshift_url('IOS',$value['uuidIOS_MEDUIM']),
						'uuidSTB_MEDIUM'   => get_timeshift_url('STB',$value['uuidSTB_MEDIUM']),
						'updatetime'  => time(),
					);
					$this->cms_channelepg->update($data_array, array('epgid'=>$value['id']));
					$data_op = '<span style="color:green;">更新</span>';
				}
				echo $data_op.'<br />';
				echo '<em>ChannelID: '.$value['channel_id'].' '.$channel['title'].' '.$value['id'].' '.$value['systemdate'].' '.$value['text'].'</em><br />';	
				echo get_timeshift_url('PC', $value['uuidPC_HIGH']).'<br />'.get_timeshift_url('IOS',$value['uuidIOS_HIGH']).'<br />';
				echo get_timeshift_url('STB',$value['uuidSTB_HIGH']).'<br />'.get_timeshift_url('PC',$value['uuidPC_MEDIUM']).'<br />';
				echo get_timeshift_url('IOS',$value['uuidIOS_MEDUIM']).'<br />'.get_timeshift_url('STB',$value['uuidSTB_MEDIUM']).'<br /><br />';	
			}
			echo '</div>';
				
			//跳转到下一页
			$next_page = $page + 1;
			page_jump('dbmover', 'import', 'program', $next_page);
			
		}else {
			//同步结束跳回本模块导航页
			showmessage(L('operation_success'),'?m=dbmover&c=import&a=init',$ms = 1250);
		}
	}
	
	
	/**
	 * 导入视频asset
	 */
	public function  asset() {
		
		//XML数据源
		$this->go3c_asset   	  		= pc_base::load_model('go3c_asset_model');
		$this->go3c_asset_content 		= pc_base::load_model('go3c_asset_content_model');
		$this->go3c_asset_poster  		= pc_base::load_model('go3c_asset_poster_model');
		$this->go3c_categoryasset 		= pc_base::load_model('go3c_categoryasset_model');
		$this->go3c_categoryasset_asset = pc_base::load_model('go3c_categoryasset_asset_model');
		
		//CMS数据表
		$this->cms_tags 		  		= pc_base::load_model('cms_tags_model');
		$this->cms_tags_data	  		= pc_base::load_model('cms_tags_data_model');
		$this->cms_video 		  		= pc_base::load_model('cms_video_model');
		$this->cms_video_data 	 		= pc_base::load_model('cms_video_data_model');
		$this->cms_video_content  		= pc_base::load_model('cms_video_content_model');
		$this->cms_video_content_data 	= pc_base::load_model('cms_video_content_data_model');
		$this->cms_video_poster 		= pc_base::load_model('cms_video_poster_model');
		$this->cms_video_poster_data 	= pc_base::load_model('cms_video_poster_data_model');
		$this->cms_column            = pc_base::load_model('cms_column_model');

		//计算总数和分页页数
		$field    	= '*';
		$sql     	= 'go3c_asset WHERE 1 '; 
		$order  	= '';
		$perpage 	= 1;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->go3c_asset->mynum($sql);
		$totalpage	= $this->go3c_asset->mytotalpage($sql, $perpage);

		// 数据源catid，转cms栏目id
		$column_names  = array("电影","电视剧","电视栏目","乐酷");
		$column_id_mapping = array();
		foreach($column_names as $column){
			$field = $this->go3c_categoryasset->get_one(array('name'=>$column),'id');
			$value = $this->cms_column->get_one(array('title'=>$column),'id');
			if(!$field['id'] || !$value['id']){
			}else{
				$column_id_mapping[$field['id']]=$value['id'];
			}
		}
		
		//如果不大于最多页数查询并开始执行数据导入
		if($page < $totalpage+1){
			
			//获取数据
			$data_array = $this->go3c_asset->listinfo('', $order, $page, $perpage);	
			
			//显示分页情况
			echo $this->go3c_asset->pages;
			
			//循环处理数据并导入
			echo '<div id="syn_msg">';			
			foreach ($data_array as $value) {
				$column_id = '0';
				//查询这个节目的分类ID
				$asset_catid = rebuild_array($this->go3c_categoryasset_asset->select(array('asset_id'=>$value['id']),'categoryasset_id','',$order = ''));
				
				foreach ($asset_catid as $assetcatid) {
					$numid = intval($assetcatid);
					
					if($numid > 40000 && $numid < 50000){ // 栏目名称单独处理
						$column_id = (array_key_exists($assetcatid,$column_id_mapping))?$column_id_mapping[$assetcatid]:'0';
						continue;			
					}

					//查询分类名称
					$asset_cat_name = $this->go3c_categoryasset->get_one(array('id'=>$assetcatid),'name');
					//检查在CMS的TAG表中是否存在，如果没有就插入，以便同步到前端
					$tags_target = $this->cms_tags->get_one(array('title' => trim($asset_cat_name['name'])), '*');
					if (!$tags_target['title']) {
						$tag_array = array(
							'title'   	 		 => trim($asset_cat_name['name']), 
							'catid'   	 		 => '4', 
							'status'     		 => '99',
							'sysadd'     		 => '1',
							'username'   		 => 'system',
							'inputtime'  		 => strtotime($value['createTime']),
							'updatetime' 		 => strtotime($value['updateTime']),
						);
						$tag_insertid = $this->cms_tags->insert($tag_array, true);
						$tag_array_data = array('id' => $tag_insertid);
						$this->cms_tags_data->insert($tag_array_data);
					}
					//将tag放到一个数组中
					$asset_cat_name_array[] = trim($asset_cat_name[name]);
				}
				
				//如果不属于$column_names指定栏目中的任何一个，则忽略此asset
				if(intval($column_id) > 0){
					//组合分类为tags
					$value[asset_tag] = implode(',',$asset_cat_name_array);
				
					$data_target = $this->cms_video->get_one(array('asset_id' => $value['id']), '*');
					if(!$data_target['asset_id']){
						$data_array = array(
							'title'   	    	 => $value['title'], 
							'asset_id'      	 => $value['id'], 
							'title'   	    	 => $value['name'], 
							'director'      	 => $value['director'], 
							'actor' 	    	 => $value['actor'],
							'tag'   	    	 => $value['asset_tag'], 
							'year_released' 	 => $value['year'],
							'run_time'      	 => $value['runTime'],
							'column_id' 		 => $column_id,
							'active' 	 	 => '1',
							'area_id' 	 	 => '1',
							'source_id' 	 	 => '1',
							'is_free' 	     	 => '0',
							'total_episodes' 	 => '',
							'parent_id' 	 	 => '',
							'episode_number'  	 => '',
							'latest_episode_num' => '',
							'rating'      		 => $value['rating'],
							'ispackage'   		 => $value['ispackage'],
							'catid'   	 		 => '54', 
							'status'     		 => '99',
							'sysadd'     		 => '1',
							'username'   		 => 'system',
							'inputtime'  		 => strtotime($value['createTime']),
							'updatetime' 		 => strtotime($value['updateTime']),
						);
						//插入视频主表并获取insert_id
						$insert_id = $this->cms_video->insert($data_array, true);
						//视频简介、介绍存在video_data表，分开来插入
						$data_array_data = array(
							'id' 		 => $insert_id, 
							'short_desc' => $value['shortName'], 
							'long_desc'  => $value['description']
						);
						$this->cms_video_data->insert($data_array_data);
						$data_op = '<span style="color:red;">插入</span>';
					

											//循环读取并插入视频的播放链接
						$video_content = $this->go3c_asset_content->select(array('asset_id' => $value['id']),'*','',$order = '');
				
						foreach ($video_content as $content_value) {
					
							//检查是否有
							$content_value_target = $this->cms_video_content->get_one(array('asset_id' => $content_value['asset_id'], 'path' => $content_value['path'], 'clarity' => $content_value['clarity']), '*');
						

							if (!$content_value_target['path']) {
								//插入video_content主表
								$content_array = array(
									'title'   	 		 => $content_value['asset_id'].'-'.$value['name'].'-'.$content_value['clarity'], 
									'asset_id'   	 	 => $content_value['asset_id'], 
									'path'   	 		 => get_vod_url('STB' ,$content_value['path']),  
									'clarity'   	 	 => $content_value['clarity'],  
									'catid'   	 		 => '64', 
									'status'     		 => '99',
									'sysadd'     		 => '1',
									'username'   		 => 'system',
									'inputtime'  		 => time(),
									'updatetime' 		 => time(),
								);
								$video_content_id = $this->cms_video_content->insert($content_array, true);
								$content_array_data = array('id' => $video_content_id);
								$this->cms_video_content_data->insert($content_array_data);
							}else{
								$content_array = array(
									'title'   	 		 => $content_value['asset_id'].'-'.$value['name'].'-'.$content_value['clarity'], 
									'path'   	 		 => get_vod_url('STB' ,$content_value['path']),  
									'updatetime' 		 => time(),
								);
								$this->cms_video_content->update($content_array, array('asset_id'=>$content_value['asset_id'], 'clarity'=>$content_value['clarity']));
							}
						}
				
				
						//循环读取并插入视频的海报
						$video_poster  = $this->go3c_asset_poster->select(array('asset_id' => $value['id']),'*','',$order = '');
						foreach ($video_poster as $poster_value) {
							//检查是否有
							$poster_value_target = $this->cms_video_poster->get_one(array('asset_id' => $poster_value['asset_id'], 'path' => $poster_value['path'], 'type' => $poster_value['type']), '*');
							if (!$poster_value_target['path']) {
								//插入video_poster主表
								$poster_array = array(
									'title'   	 		 => $poster_value['asset_id'].'-'.$value['name'].'-'.$poster_value['type'], 
									'asset_id'   	 	 => $poster_value['asset_id'], 
									'path'   	 		 => get_img_url($poster_value['path']),  
									'type'   	 		 => $poster_value['type'],  
									'catid'   	 		 => '65', 
									'status'     		 => '99',
									'sysadd'     		 => '1',
									'username'   		 => 'system',
									'inputtime'  		 => time(),
									'updatetime' 		 => time(),
								);
								$video_poster_id = $this->cms_video_poster->insert($poster_array, true);
								$poster_array_data = array('id' => $video_poster_id);
								$this->cms_video_poster_data->insert($poster_array_data);
							}else {
								$poster_array = array(
									'title'   	 		 => $poster_value['asset_id'].'-'.$value['name'].'-'.$poster_value['type'], 
									'asset_id'   	 	 => $poster_value['asset_id'], 
									'path'   	 		 => get_img_url($poster_value['path']),  
									'type'   	 		 => $poster_value['type'],  
									'updatetime' 		 => time(),
								);
								$this->cms_video_poster->update($poster_array, array('asset_id'=>$poster_value['asset_id'], 'type'=>$poster_value['type']));
							}
						}

					}else{
					
						$data_op = '<span style="color:green;">跳过</span>';
					
					}
				}else{
					
					$data_op = '<span style="color:blue;">忽略</span>';
					
				}
				
			

					
				//调试代码
				//echo '<pre>';print_r($value);
				//echo '<pre>';print_r($data_array);
				//echo '<pre>';print_r($data_array_data);
				//echo '<pre>';print_r($video_content);
				//echo '<pre>';print_r($video_poster);
					
				
				//输出提示信息
				echo $data_op.' ';
				echo '<em>'.$value['id'].' '.$value['title'].'</em><br />';
				echo $value['director'].' '.$value['actor'].' '.$value['year'].'<br />';	
				echo $value['asset_tag'].'<br />';	
				foreach ($video_content as $content_value) {
					echo $content_value['asset_id'].' '.$content_value['path'].' '.$content_value['clarity'].'<br />';	
				}
				foreach ($video_poster as $poster_value) {
					echo $poster_value['asset_id'].' '.$poster_value['path'].' '.$poster_value['type'].'<br />';	
				}
					
					
			}
			
			echo '</div>';
				
			//跳转到下一页
			$next_page = $page + 1;
			page_jump('dbmover', 'import', 'asset', $next_page);
			
		}else {
			//同步结束跳回本模块导航页
			showmessage(L('operation_success'),'?m=dbmover&c=import&a=init',$ms = 1250);
		}
		
	}
	
}
?>