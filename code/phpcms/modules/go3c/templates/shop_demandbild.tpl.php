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
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<div class="pad-lr-10">
<form name="myform"  action="" method="get">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="openbild" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<?php if($_SESSION['roleid']=='1'){?>
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
	<tr>
		<td>
		<div class="explain-col">
			
			商品名称：<input id= "mac_wire" name="mac_wire" type="text" value="<?php if(isset($mac_wire)) echo $mac_wire;?>" class="input-text" size="15"/>&nbsp;
			<?php echo L('ktv_page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			
			<input type="button" value="添加" onclick="addnew()" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<?php }?>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <thead>
            <tr>
            	<th  align="left" width="20"><input type="checkbox" value="全选" id="check_box" onclick="selectall('userid[]');">全选</th>
            	<th width='30' align="left">编号</th>
				<th width='30' align="left">名称</th>
				<th width='30' align="left">更新时间</th>
				<th width='20' align="left">商品类型</th>
				<th width='20' align="left">操作</th>
            </tr>
        </thead>
    <tbody>
	<?php if(count($data)){foreach($data as $v){?>  
	<tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['userid']?>" name="userid[]"></td>
		<td align="center"><?php echo $v['']?></td>
		<td align="left"><?php echo $v[''] ?></td>
		<td align="center"><?php echo $v['']?></td>
		<td align="center"><?php echo $v['']?></td>
		<td align="left"><?php echo $v['']?>
			<a href="">编辑</a>
			<a href="">发布</a>
			<a href="" onclick="return confirm('<?php echo L('确定删除吗')?>')">删除</a>
		</td>
	</tr>
	<?php }} ?>
</tbody>
  	</table>	
	</div>
    <div id="pages">
    	<div style="float:left;">
    		<label for="check_box"><?php echo L('select_all')?></label>
    		<h>选中项</h>
    		<select name="" id="">
				  <option value="发布" <?php if($category == 'vod'){ echo 'selected';}?>>发布</option>
				  <option value="编辑" <?php if($category == 'live'){ echo 'selected';}?>>编辑</option>
				  <option value="删除" <?php if($category == 'live'){ echo 'selected';}?>>删除</option>
			</select> &nbsp;
			<input type='button' value='确定'></input>
    	</div>
    	<?php echo $multipage;?>
    </div>
</form>
</div>
</body>
</html>
<script type="text/javascript">

function addnew(){
	location.href ='?m=go3c&c=tvuser&a=addCarton&catid=&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

</script>

<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
</script>
