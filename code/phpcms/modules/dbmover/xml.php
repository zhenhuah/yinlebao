<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class xml extends admin {

	public function __construct() {
		pc_base::load_app_func('global');
		parent::__construct();
		dbmover_css();
	}   	
	
	public function init() {
		echo <<<EOT
		  <ul>
		  <li><a target="right" href="import/import_cate.php">XML采集-导入资源分类</a></li>
		  <li><a target="right" href="import/import_channel.php">XML采集-导入channel数据</a></li>
		  <li><a target="right" href="import/import_epg.php">XML采集-导入epg数据</a></li>
		  <li><a target="right" href="import/import_export.php">XML采集-视频节目</a></li>
		  </ul>
EOT;
	}
	
	public function cate() {
		echo <<<EOT
		  <ul>
		  <li><a target="right" href="import/import_cate.php">XML采集-导入资源分类</a></li>
		  </ul>
EOT;
	}
	
	public function channel() {
		echo <<<EOT
		  <ul>
		  <li><a target="right" href="import/import_channel.php">XML采集-导入channel数据</a></li>
		  </ul>
EOT;
	}
	
	public function epg() {
		echo <<<EOT
		  <ul>
		  <li><a target="right" href="import/import_epg.php">XML采集-导入epg数据</a></li>
		  </ul>
EOT;
	}
	
	public function export() {
		echo <<<EOT
		  <ul>
		  <li><a target="right" href="import/import_export.php">XML采集-视频节目</a></li>
		  </ul>
EOT;
	}
	
}
?>