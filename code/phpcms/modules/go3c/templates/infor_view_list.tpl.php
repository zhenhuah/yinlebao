<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<style type="text/css">
	body{margin:0 10px;}
</style>
<div class="table-list">
<form action="?m=go3c&c=task&a=listorder" method="post" name="myform2">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="10" align="center">排序</th>
			<th width="30" align="center">序号</th>
			<th width="30" align="center">任务ID</th>
			<th width="100" align="center">所属推荐位</th>			
			<th width="40" align="center">资讯ID</th>
			<th width="100" align="center">名称</th>				
			<th width='30' align="center">图片</th>	
			<th width='30' align="center">状态</th>
			<th width="50" align="center">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(!empty($infor_list)){foreach($infor_list as $infor){?>
	<tr>
	<td align="center"><input type="text" class="input-text-c input-text" value="<?php echo $infor['videoSort']?>" size="3" name="listorders[<?php echo $infor['preId']?>]"></td>
	<td align="center"><?php echo $infor['preId']?></td>
	<td align="center"><?php echo $infor['taskId']?></td>
	<td align="center"><?php echo $infor['posidInfo']?></td>
	<td align="center"><?php echo $infor['videoId']?></td>
	<td align="center"><?php echo $infor['videoTitle']?></td>
	<td align="center">
		<a href="<?php echo $infor['videoImg']?>" style="color:green" target="_blank">浏览</a>
	</td>
	<td align="center"><?php if($infor['status'] =='Y'){echo '可用';}else{echo "<font color='red'>不可用</font>";}?></td>
	<td align="center">
	<a style="color:red" href="javascript:confirmurl('?m=go3c&c=infor&a=deleteTaskinfor&preId=<?php echo $infor['preId'];?>&taskId=<?php echo $infor['taskId'];?>','你确定要执行该操作吗？')">删除 </a>
	</td>
	</tr>
	<?php }} ?>

	<?php if(empty($infor_list)){?>
	<tr>
	<td align="center" colspan="10">暂无数据</td>
	</tr>
	<?php }?>
	</tbody>
    </table>
	<?php if(!empty($infor_list)){?>
	<input type="hidden" value="<?php echo $_SESSION['pc_hash'];?>" name="pc_hash">
	<div class="btn"><input type="submit" value="排序" name="dosubmit" class="button"></div>
	<?php }?>	
</form>
</div>
<div id="pages"><?php echo $pages;?></div>
</div>
</body>
</html>