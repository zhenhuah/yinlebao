<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class index extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		dbmover_css();
	}   	
	
	public function init() {
		dbmover_index_link();
	}
	
	/**
	 * Tag同步
	 */
	public function  tags() {
		
		$this->cms_tags = pc_base::load_model('cms_tags_model');
		$this->tv_tag   = pc_base::load_model('tv_tag_model');
		
		$infos = $this->cms_tags->select('','id, title','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_tag->get_one(array('tag_id'=>$value['id']), 'tag_id, tag_name');
			if(!$data_target['tag_name'] && $value['title']){
				$data_array = array('tag_id' => $value['id'], 'tag_name' => $value['title']);
				$this->tv_tag->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				if($value['title']){
					$data_array = array('tag_name' => $value['title']);
					$this->tv_tag->update($data_array, array('tag_id'=>$value['tag_id']));
					$data_op = '<span style="color:green;">更新</span>';
				}
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 直播频道分类','?m=dbmover&c=index&a=channel_category',$ms = 500);
		
	}

	/**
	 * 直播频道分类
	 */
	public function  channel_category() {
		
		$this->cms_channel_category = pc_base::load_model('cms_channel_category_model');
		$this->tv_channel_category  = pc_base::load_model('tv_channel_category_model');
		
		$infos = $this->cms_channel_category->select('','id, title,icon_img_url','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_channel_category->get_one(array('category_id'=>$value['id']), 'category_id, category_name, icon_img_url');
			if(!$data_target['category_name']){
				$data_array = array('category_id' => $value['id'], 'category_name' => $value['title'], 'icon_img_url' => $value['icon_img_url'],'seq_number' => $value['listorder']);
				$this->tv_channel_category->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('category_name' => $value['title'], 'icon_img_url' => $value['icon_img_url'],'seq_number' => $value['listorder']);
				$this->tv_channel_category->update($data_array, array('category_id'=>$value['id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 终端类型','?m=dbmover&c=index&a=term_type',$ms = 500);
	}

	/**
	 * 终端类型
	 */
	public function  term_type() {
		
		$this->cms_term_type = pc_base::load_model('cms_term_type_model');
		$this->tv_term_type  = pc_base::load_model('tv_term_type_model');
		
		$infos = $this->cms_term_type->select('','id, title','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_term_type->get_one(array('id'=>$value['id']), 'id, description');
			if(!$data_target['description']){
				$data_array = array('id' => $value['id'], 'description' => $value['title']);
				$this->tv_term_type->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('description' => $value['title']);
				$this->tv_term_type->update($data_array, array('id'=>$value['id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 栏目','?m=dbmover&c=index&a=column',$ms = 500);
		
	}

	/**
	 * 栏目
	 */
	public function  column() {
		
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->tv_column  = pc_base::load_model('tv_column_model');
		
		$infos = $this->cms_column->select('','id, title','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_column->get_one(array('col_id'=>$value['id']), 'col_id, col_name');
			if(!$data_target['col_name']){
				$data_array = array('col_id' => $value['id'], 'col_name' => $value['title']);
				$this->tv_column->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('col_name' => $value['title']);
				$this->tv_column->update($data_array, array('col_id'=>$value['id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 栏目下按地域分类','?m=dbmover&c=index&a=column_content_area',$ms = 500);
	}

	/**
	 * 栏目下按地域分类
	 */
	public function  column_content_area() {
		
		$this->cms_column_content_area = pc_base::load_model('cms_column_content_area_model');
		$this->tv_column_content_area  = pc_base::load_model('tv_column_content_area_model');
		
		$infos = $this->cms_column_content_area->select('','id, title, col_id','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_column_content_area->get_one(array('area_id'=>$value['id']), 'area_id, area_name, col_id');
			if(!$data_target['area_name']){
				$data_array = array('area_id' => $value['id'], 'area_name' => $value['title'], 'col_id' => $value['col_id']);
				$this->tv_column_content_area->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('area_name' => $value['title'], 'col_id' => $value['col_id']);
				$this->tv_column_content_area->update($data_array, array('area_id'=>$value['id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 推荐类型','?m=dbmover&c=index&a=recomm_video_type',$ms = 500);
		
	}

	/**
	 * 推荐类型
	 */
	public function  recomm_video_type() {
		
		$this->cms_position = pc_base::load_model('cms_position_model');
		$this->tv_recomm_video_type  = pc_base::load_model('tv_recomm_video_type_model');
		
		$infos = $this->cms_position->select('','term_id, type_id, name','',$order = 'posid ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_recomm_video_type->get_one(array('term_id'=>$value['term_id'],'type_id'=>$value['type_id']), 'term_id, type_id, description');
			if(!$data_target['description']){
				$data_array = array('term_id' => $value['term_id'], 'type_id' => $value['type_id'], 'description' => $value['name']);
				$this->tv_recomm_video_type->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('description' => $value['name']);
				$this->tv_recomm_video_type->update($data_array, array('term_id'=>$value['term_id'],'type_id'=>$value['type_id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['name'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 栏目下按内容类型分类','?m=dbmover&c=index&a=column_content_cate',$ms = 500);

	}

	/**
	 * 栏目下按内容类型分类
	 */
	public function  column_content_cate() {
		
		$this->cms_column_content_cate = pc_base::load_model('cms_column_content_cate_model');
		$this->tv_column_content_category  = pc_base::load_model('tv_column_content_category_model');
		
		$infos = $this->cms_column_content_cate->select('','id, title, col_id','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_column_content_category->get_one(array('cat_id'=>$value['id'],'col_id'=>$value['col_id']), 'cat_id, cat_name, col_id');
			if(!$data_target['cat_name']){
				$data_array = array('cat_id' => $value['id'], 'col_id' => $value['col_id'], 'cat_name' => $value['title']);
				$this->tv_column_content_category->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('cat_name' => $value['title']);
				$this->tv_column_content_category->update($data_array, array('cat_id'=>$value['id'],'col_id'=>$value['col_id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['title'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步 栏目终端类型映射','?m=dbmover&c=index&a=column_mapping',$ms = 500);
		
	}

	/**
	 * 栏目终端类型映射
	 */
	public function  column_mapping() {
		
		$this->cms_column_mapping = pc_base::load_model('cms_column_mapping_model');
		$this->tv_column_mapping  = pc_base::load_model('tv_column_mapping_model');
		
		$infos = $this->cms_column_mapping->select('','col_id, term_id, listorder','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_column_mapping->get_one(array('term_id'=>$value['term_id'],'col_id'=>$value['col_id']), 'term_id, col_id, seq_number');
			if($data_target['seq_number']==''){
				$data_array = array('term_id' => $value['term_id'], 'col_id' => $value['col_id'], 'seq_number' => $value['listorder']);
				$this->tv_column_mapping->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}else{
				$data_array = array('seq_number' => $value['listorder']);
				$this->tv_column_mapping->update($data_array, array('term_id'=>$value['term_id'],'col_id'=>$value['col_id']));
				$data_op = '<span style="color:green;">更新</span>';
			}
			//echo $data_op.' : '.$value['term_id'].' : '.$value['col_id'].' : '.$value['listorder'].'<br />';
		}
		echo '</div>';
		showmessage('开始同步清晰度信息','?m=dbmover&c=index&a=video_quality',$ms = 500);

	}
	
	/**
	 * 清晰度信息
	 */
	public function  video_quality() {
		
		$this->dbmover_link_type  = pc_base::load_model('dbmover_link_type_model');

		$this->tv_video_quality  = pc_base::load_model('tv_video_quality_model');
		
		$infos = $this->dbmover_link_type->select('','*','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_video_quality->get_one(array('id'=>$value['id']), 'id');

			if($data_target['id']){
				$data_array = array('description'=> $value['description'].'-'.$value['code_rate'].'-'
.$value['display_width'].'x'.$value['display_height'].'-'.$value['format']);
				$this->tv_video_quality->update($data_array, array('id'=>$value['id']));
				$data_op = '<span style="color:green;">更新</span>';

			}else{
				$data_array = array('id' => $value['id'],'description'=> $value['description'].'-'.$value['code_rate'].'-'
.$value['display_width'].'x'.$value['display_height'].'-'.$value['format']);
				$this->tv_video_quality->insert($data_array);
				$data_op = '<span style="color:red;">插入</span>';
			}
			//echo $data_op.' : '.$value['term_id'].' : '.$value['col_id'].' : '.$value['listorder'].'<br />';
		}
		echo '</div>';
		showmessage('同步结束','?m=dbmover&c=index&a=position',$ms = 500);

	}

	
	
	/**
	 * 推荐位
	 */
	public function  position() {
		
		$this->cms_position = pc_base::load_model('cms_position_model');
		$this->tv_position  = pc_base::load_model('tv_recomm_video_type_model');
		
		$infos = $this->cms_position->select('','*','',$order = 'posid ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->tv_position->get_one(array('type_id'=>$value['type_id'], 'term_id'=>$value['term_id'], 'spid'=>$value['spid']), 'description');
			if(!$data_target['description']){
				if($value['type_id'] && $value['term_id'] && $value['spid']){
					$data_array = array(
						'description'   => $value['name'],
						'type_id'   	=> $value['type_id'],
						'term_id'   	=> $value['term_id'],
						'spid'   		=> $value['spid'],
					);
					$this->tv_position->insert($data_array);
				}
			}
		}
		echo '</div>';
		$this->tv_position->query("delete from recomm_video_type where spid = ''");	
		showmessage('同步结束','?m=dbmover&c=index&a=init',$ms = 500);
		
	}
	
}
?>