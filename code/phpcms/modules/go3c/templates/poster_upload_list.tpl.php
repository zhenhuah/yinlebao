<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<link href="<?php echo CSS_PATH?>default.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload.queue.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>fileprogress.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>handlers.js"></script>
<style type="text/css">
	body{margin:0 10px;}
	.table-list{margin-top:10px;}
	.table-list div{ float:left;}
</style>
	<form id="form1" action="upload.php" method="post" enctype="multipart/form-data">
	<input type="hidden" value="go3c" name="m">
	<input type="hidden" value="authserach" name="c">
	<input type="hidden" value="tags" name="a">
	<input type="hidden" value="query" name="mode">
	<div class="lst_lxpg clearfix" style="position:relative;">
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
    <div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">上传图片队列</span>
	</div>
	<div id="divStatus">0 图片上传</div>
		<div>
		<?php echo $asset_id ?>
			<input id="spanButtonPlaceHolder" type="button" value="选择" />
			<input id="btnCancel" type="button" value="取消" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
		</div>
	</div>
</form>
</body>
</html>
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "statics/js/swfupload.swf",
				upload_url: "upload2.php",
				file_post_name : "Filedata",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>",
							  "asset_id" : "<?php echo $_SESSION['asset_id'];?>"
				},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "statics/js/select.png",
				button_width: "80",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<input class="theFont" type="button" value="选择" />选择图片',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
	</script>
