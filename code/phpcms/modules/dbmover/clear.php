<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class clear extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		dbmover_css();
	}  	
	
	public function init() {
		dbmover_clear_link();
	}
	
	/**
	 * Tag
	 */
	public function  tags() {

		$this->cms_tags = pc_base::load_model('cms_tags_model');
		$this->tv_tag   = pc_base::load_model('tv_tag_model');
		
		$infos = $this->tv_tag->select('','*','',$order = 'tag_id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_tags->get_one(array('id'=>$value['tag_id']), 'title');
			if(!$data_target['title']){
				$this->tv_tag->delete(array('tag_id'=>$value['tag_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['tag_name'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 直播频道分类','?m=dbmover&c=clear&a=channel_category',$ms = 500);
		
	}

	/**
	 * 直播频道分类
	 *
	 */
	public function  channel_category() {

		$this->cms_channel_category = pc_base::load_model('cms_channel_category_model');
		$this->tv_channel_category  = pc_base::load_model('tv_channel_category_model');
		
		$infos = $this->tv_channel_category->select('','*','',$order = 'category_id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_channel_category->get_one(array('id'=>$value['category_id']), 'title');
			if(!$data_target['title']){
				$this->tv_channel_category->delete(array('category_id'=>$value['category_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['category_name'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 终端类型','?m=dbmover&c=clear&a=term_type',$ms = 500);
	}

	/**
	 * 终端类型
	 *
	 */
	public function  term_type() {
		
		$this->cms_term_type = pc_base::load_model('cms_term_type_model');
		$this->tv_term_type  = pc_base::load_model('tv_term_type_model');
		
		$infos = $this->tv_term_type->select('','*','',$order = 'id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_term_type->get_one(array('id'=>$value['id']), 'title');
			if(!$data_target['title']){
				$this->tv_term_type->delete(array('id'=>$value['id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['description'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 栏目','?m=dbmover&c=clear&a=column',$ms = 500);
	}

	/**
	 * 栏目
	 */
	public function  column() {
		
		$this->cms_column = pc_base::load_model('cms_column_model');
		$this->tv_column  = pc_base::load_model('tv_column_model');
		
		$infos = $this->tv_column->select('','*','',$order = 'col_id ASC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_column->get_one(array('id'=>$value['col_id']), 'title');
			if(!$data_target['title']){
				$this->tv_column->delete(array('col_id'=>$value['col_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['col_name'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 栏目下按地域分类','?m=dbmover&c=clear&a=column_content_area',$ms = 500);
	}

	/**
	 * 栏目下按地域分类
	 */
	public function  column_content_area() {

		$this->cms_column_content_area = pc_base::load_model('cms_column_content_area_model');
		$this->tv_column_content_area  = pc_base::load_model('tv_column_content_area_model');
		
		$infos = $this->tv_column_content_area->select('','*','',$order = 'area_id DESC,col_id DESC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_column_content_area->get_one(array('id'=>$value['area_id'], 'col_id'=>$value['col_id']), 'title');
			if(!$data_target['title']){
				$this->tv_column_content_area->delete(array('col_id'=>$value['col_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['area_name'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 推荐类型','?m=dbmover&c=clear&a=recomm_video_type',$ms = 500);
	}

	/**
	 * 推荐类型
	 *
	 */
	public function  recomm_video_type() {
		
		$this->cms_position = pc_base::load_model('cms_position_model');
		$this->tv_recomm_video_type  = pc_base::load_model('tv_recomm_video_type_model');
		
		$infos = $this->tv_recomm_video_type->select('','*','',$order = 'term_id DESC, type_id DESC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_position->get_one(array('term_id'=>$value['term_id'], 'type_id'=>$value['type_id']), 'name');
			if(!$data_target['name']){
				$this->tv_recomm_video_type->delete(array('term_id'=>$value['term_id'], 'type_id'=>$value['type_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['description'].'<br />';
		}
		echo '</div>';
		showmessage(L('开始清理 栏目下按内容类型分类'),'?m=dbmover&c=clear&a=column_content_cate',$ms = 500);
	}

	/**
	 * 栏目下按内容类型分类
	 *
	 */
	public function  column_content_cate() {
		
		$this->cms_column_content_cate = pc_base::load_model('cms_column_content_cate_model');
		$this->tv_column_content_category  = pc_base::load_model('tv_column_content_category_model');
		
		$infos = $this->tv_column_content_category->select('','*','',$order = 'cat_id DESC, col_id DESC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_column_content_cate->get_one(array('id'=>$value['cat_id'], 'col_id'=>$value['col_id']), 'title');
			if(!$data_target['title']){
				$this->tv_column_content_category->delete(array('cat_id'=>$value['cat_id'], 'col_id'=>$value['col_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['cat_name'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 栏目终端类型映射','?m=dbmover&c=clear&a=column_mapping',$ms = 500);
	}

	/**
	 * 栏目终端类型映射
	 *
	 */
	public function  column_mapping() {
		
		$this->cms_column_mapping = pc_base::load_model('cms_column_mapping_model');
		$this->tv_column_mapping  = pc_base::load_model('tv_column_mapping_model');
		
		$infos = $this->tv_column_mapping->select('','*','',$order = 'term_id DESC, col_id DESC');
		
		echo '<div id="syn_msg">';
		foreach ($infos as $value) {
			$data_target = $this->cms_column_mapping->get_one(array('term_id'=>$value['term_id'], 'col_id'=>$value['col_id']), 'listorder');
			if($data_target['listorder']==''){
				$this->tv_column_mapping->delete(array('term_id'=>$value['term_id'], 'col_id'=>$value['col_id']));
				$data_op = '<span style="color:red;">删除</span>';
			}else{
				$data_op = '<span style="color:green;">跳过</span>';
			}
			//echo $data_op.' : '.$value['term_id'].' : '.$value['col_id'].' : '.$value['seq_number'].'<br />';
		}
		echo '</div>';
		showmessage('开始清理 推荐位','?m=dbmover&c=clear&a=position',$ms = 500);
	}
	
	
	/**
	 * 推荐位
	 *
	 */
	public function  position() {
		$this->position  = pc_base::load_model('tv_recomm_video_type_model');
		$this->position->query("TRUNCATE table recomm_video_type");	
		showmessage('开始同步 直播频道分类','?m=dbmover&c=index&a=channel_category',$ms = 500);
	}
	
	
}
?>