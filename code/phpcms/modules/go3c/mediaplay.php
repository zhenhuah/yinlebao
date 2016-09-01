<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class mediaplay extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		//获取当前用户信息
		$userid = $_SESSION['userid'];
		$this->spid_db = pc_base::load_model('admin_model');
		$this->current_spid = $this->spid_db->get_one(array('userid'=>$userid));
	}   	
	
	public function init() {
		
	}
	
	/**
	 * 单屏播放
	 *
	 */
	public function mediaplay_view() {
		$id = intval($_GET['id']);
		$assetid = $_GET['assetid'];
		$this->cms_video = pc_base::load_model('cms_video_model');
		$this->cms_video_content   = pc_base::load_model('cms_video_content_model');
		$cms_video   = $this->cms_video->get_one(array('asset_id'=>$assetid));
		$cms_video_content   = $this->cms_video_content->select(array('asset_id'=>$assetid));

		include $this->admin_tpl('mediaplay_view');
	}

	/**
	 * 多屏播放
	 *
	 */
	public function mediaplay_multiview() {
		$assetid = $_GET['assetid'];
		$this->cms_video = pc_base::load_model('cms_video_model');
		$this->cms_video_content   = pc_base::load_model('cms_video_content_model');
		$cms_video   = $this->cms_video->get_one(array('asset_id'=>$assetid));
		$cms_video_content   = $this->cms_video_content->select(array('asset_id'=>$assetid));

		include $this->admin_tpl('mediaplay_multiview');
	}
	
	/**
	 * 直播的链接播放
	 */
	public function channel_play(){
		$channel_id = $_GET['channel_id'];
		$id  = $_GET['id'];
		$this->cms_channel = pc_base::load_model('cms_channel_model');
		$this->cms_channel_play_info = pc_base::load_model('cms_channel_play_info_model');
		$cms_video   = $this->cms_channel->get_one(array('channel_id'=>$channel_id));
		$cms_video_content   = $this->cms_channel_play_info->select(array('channel_id'=>$channel_id));
		include $this->admin_tpl('channel_play');
	}
	/**
	 * 推荐视频
	 */
	public function task_play(){
		$adId  = $_GET['adId'];
		$this->adverts_db = pc_base::load_model('cms_pre_adverts_model');
		$cms_video   = $this->adverts_db->get_one(array('adId'=>$adId));
		include $this->admin_tpl('task_play');
	}
	//歌曲链接播放
	public function mediaplaysong_view(){
		$vid  = $_GET['vid'];
		$this->t_song_play_info = pc_base::load_model('ktv_t_song_play_info_model');
		$this->t_songs = pc_base::load_model('ktv_t_songs_model');
		$cms_video   = $this->t_songs->get_one(array('vid'=>$vid));
		$cms_video_content   = $this->t_song_play_info->select(array('vid'=>$vid));
		include $this->admin_tpl('mediaplaysong_view');
	}
}
?>
