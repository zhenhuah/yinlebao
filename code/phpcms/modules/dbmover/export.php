<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class export extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		dbmover_css();
	}  
	
	public function init() {
		//dbmover_export_link();
	}
	
	public function channel() {

		$this->db = pc_base::load_model('cms_channel_model');		
		$field    	= '*';
		$sql     	= 'v9_channel '; 
		$order  	= 'ORDER BY id ASC';
		$perpage 	= 1;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);

		$this->tv_channel = pc_base::load_model('tv_channel_model');
		$this->tv_channel_image = pc_base::load_model('tv_channel_image_model');
		$this->tv_channel_play_info = pc_base::load_model('tv_channel_play_info_model');

		$terms = array(1,2,3,4);
		$high_uuidtags = array(1 => "uuidSTB_HIGH",2 => "uuidIOS_HIGH",3 => "uuidIOS_HIGH",4 => "uuidPC_HIGH");
		$medium_uuidtags = array(1 => "uuidSTB_MEDIUM",2 => "uuidIOS_MEDUIM",3 => "uuidIOS_MEDUIM",4 => "uuidPC_MEDIUM");
		
		if($page < $totalpage+1){

			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $totalnum.' '.$totalpage.'<br />';
			echo $multipage.' ';
			echo '<pre>';
			print_r($data_array);
			
			/**
			 * 插入前端代码开始
			 */
			
			foreach ($data_array as $value) {
				
				$target = $this->db->get_one(array('id' => $value['id']),'id,title');
				
				if($target['published'] == 0 || $target['published'] == 3){
					
					$newdata = array(
						'channel_id'  		=> intval($value['channel_id']),
						'channel_name' 		=> $value['title'],
						'id'    			=> $value['id'],
						'catid'    	    	=> '63'
					);
					
					$this->tv_channel->insert($newdata, true);
					
					$data_op = '<span style="color:red;">插入</span>'.' : '.$value['title'].'<br />';
					$this->db->update(array('published'=>1), array('id'=>$value['id']));

					foreach($terms as $term){
						
						$newimage = array(
							'channel_id' => intval($value['channel_id']),
							'term_id' => $term,
							'list_img_url' => $value['imgpath'],
							'details_img_url' => $value['imgpath']
						);
						$this->tv_channel_image->insert($newimage, true);
						
						if($value[$high_uuidtags[$term]]){
							$highurl = array(
								'channel_id' => intval($value['channel_id']),
								'term_id' => $term,
								'quality' => '3',
								'uuid' => $value[$high_uuidtags[$term]],
							);
							$this->tv_channel_play_info->insert($highurl, true);
						}
						
						if($value[$medium_uuidtags[$term]]){
							$mediumurl = array(
								'channel_id' => intval($value['channel_id']),
								'term_id' => $term,
								'quality' => '2',
								'uuid' => $value[$medium_uuidtags[$term]],
							);
							$this->tv_channel_play_info->insert($mediumurl, true);
						}	
							
					}
					$data_op.= '<span style="color:red;">插入图片,播放链接</span>';

				}else{
					$data_op = '<span style="color:green;">跳过</span>'.' : '.$value['title'].'<br />';
				}
				echo $data_op;
			}
			
			/**
			 * 插入前端代码结束
			 */
			
			
			$next_page = $page + 1;
			page_jump('dbmover', 'export', 'channel', $next_page);
			
		}else{
			showmessage(L('operation_success'),'?m=dbmover&c=export&a=init',$ms = 1250);
		}
		
	}
	
	public function program() {

		$this->db = pc_base::load_model('cms_channelepg_model');		
		$field    	= '*';
		$sql     	= 'v9_channelepg '; 
		$order  	= 'ORDER BY id ASC';
		$perpage 	= 1;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);

		$this->epg = pc_base::load_model('tv_epg_model');

		if($page < $totalpage+1){

			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $totalnum.' '.$totalpage.'<br />';
			echo $multipage.' ';
			echo '<pre>';
			print_r($data_array);
			
			/**
			 * 插入前端代码开始
			 */
			
			foreach ($data_array as $value) {
				$target = $this->db->get_one(array('id' => $value['id']),'published');
				if($target['published'] == 0 || $target['published'] == 3){
					$newdata = array(
						'channel_id'  => intval($value['channel_id']),
						'title' 	  => $value['title'],
						'img' 	  => $value['url'],
						'description'      => $value['text'],
						'start_time'      => $value['starttime'],
						'end_time'   => $value['endtime']
					);
					$this->epg->insert($newdata, true);
					$data_op = '<span style="color:red;">插入</span>';

					$this->db->update(array('published'=>1), array('id'=>$value['id']));
				}else{
					$data_op = '<span style="color:green;">跳过</span>';
				}
				echo $data_op.' : '.$value['title'].'<br />';
			}
			
			/**
			 * 插入前端代码结束
			 */
			
			
			$next_page = $page + 1;
			page_jump('dbmover', 'export', 'program', $next_page);
			
		}else{
			showmessage(L('operation_success'),'?m=dbmover&c=export&a=init',$ms = 1250);
		}
		
	}
		
	public function asset() {

		$this->db = pc_base::load_model('cms_video_model');		
		$field    	= '*';
		$sql     	= 'v9_video '; 
		$order  	= 'ORDER BY id ASC';
		$perpage 	= 1;
		$page    	= isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$totalnum	= $this->db->mynum($sql);
		$totalpage	= $this->db->mytotalpage($sql, $perpage);
		
		$this->cms_video_data  		= pc_base::load_model('cms_video_data_model');
		$this->cms_video_content  		= pc_base::load_model('cms_video_content_model');
		$this->cms_video_poster 		= pc_base::load_model('cms_video_poster_model');

		$this->video                        = pc_base::load_model('tv_video_model');
		$this->video_img                    = pc_base::load_model('tv_video_image_model');
		$this->video_playinfo                  = pc_base::load_model('tv_video_play_info_model');

		if($page < $totalpage+1){

			$data_array = $this->db->mylistinfo($field, $sql, $order, $page, $perpage); 
			$multipage  = $this->db->pages;
			//echo $totalnum.' '.$totalpage.'<br />';
			echo $multipage.' ';
			echo '<pre>';
			//print_r($data_array);
			
			/**
			 * 插入前端代码开始
			 */
			foreach ($data_array as $value) {
				$target = $this->db->get_one(array('id' => $value['id']),'published');
				if($target['published'] == 0 || $target['published'] == 3){
					$extend_data  = $this->cms_video_data->get_one(array('id' => $value['id']),'short_desc,long_desc');
					//print_r($extend_data);
					$newdata = array(

						'vid' =>   $value['asset_id'],
						'name' =>  $value['title'],
						'column_id' => intval($value['column_id']),
						'short_desc' => $extend_data['short_desc'],
						'long_desc' => $extend_data['long_desc'],
						'active' => $value['active'],
						'time_created'=> date('Y-m-d H:i:s',$value['inputtime']),
						'time_updated'=> date('Y-m-d H:i:s',$value['updatetime']),
						'created_by' => 'root', 
						'play_count' => 0,
						'area_id' => $value['area_id'],
						'director' =>  $value['director'],
						'source_id' =>  $value['source_id'],
						'is_free'  =>  $value['is_free'],
						'run_time' =>  $value['run_time'],
						'year_released' =>  $value['year_released'],
						'total_episodes' =>  $value['total_episodes'], 
						'parent_id' =>  $value['parent_id'],
						'episode_number' =>  $value['episode_number'],
						'latest_episode_number' =>  $value['latest_episode_num']
					);
					print_r($newdata);
					$this->video->insert($newdata, true);
					$data_op = '<span style="color:red;">插入</span>';
					
					/*插入图片*/
					$video_posters = $this->cms_video_poster->select(array('asset_id' => $value['asset_id']),'*','',$order = '');
					foreach($video_posters as $poster){
						$newimg = array(
							'vid' => $poster['asset_id'],
							'img_id' => intval($poster['id']),
							'img_url' => $poster['path'],
							'img_type' => intval($poster['type'])
						);
						//print_r($newimg);
						$this->video_img->insert($newimg, true);
					}

					/*插入播放*/
					$video_contents = $this->cms_video_content->select(array('asset_id' => $value['asset_id']),'*','',$order = '');
					foreach($video_contents as $content){
						$newplayinfo = array(
							'vid' => $content['asset_id'],
							'quality' => $content['clarity'],
							'play_url' => $content['path']
						);
						//print_r($newplayinfo);
						$this->video_playinfo->insert($newplayinfo, true);
					}

					$this->db->update(array('published'=>1), array('id'=>$value['id']));
				}else{
					$data_op = '<span style="color:green;">跳过</span>';
				}
				echo $data_op.' : '.$value['title'].'<br />';
			}			
			
			/**
			 * 插入前端代码结束
			 */
			
			
			$next_page = $page + 1;
			page_jump('dbmover', 'export', 'asset', $next_page);
			
		}else{
			showmessage(L('operation_success'),'?m=dbmover&c=export&a=init',$ms = 1250);
		}
		
	}
	
}
?>
