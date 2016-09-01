<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div style="margin-left:30px">
<a style="color:blue;font-size:18px" href="?m=go3c&c=tvuser&a=user_feedback_test">返回</a><br><br>
<?php 
echo '<pre>'. $data[0]['description'].'</pre>' ;
?>
</div>