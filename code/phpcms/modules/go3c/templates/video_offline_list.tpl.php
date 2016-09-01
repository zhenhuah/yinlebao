<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="offline" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=video&a=offline">全部数据</a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />&nbsp;
			名称：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />&nbsp;
            状态：<select name="filter">
				<option value='' <?php if($_GET['filter']==0) echo 'selected';?>>全部</option>
				<option value='1' <?php if($_GET['filter']==1) echo 'selected';?>>在线</option>
				<option value='2' <?php if($_GET['filter']==2) echo 'selected';?>>已申请下线</option>
				<option value='3' <?php if($_GET['filter']==3) echo 'selected';?>>下线</option>
			</select>&nbsp;
            类型：<select name="column_id">
				<option value='' <?php if($_GET['column_id']==0) echo 'selected';?>>全部</option>
            <?php {foreach($cms_column as $key=>$column){?>
           		 <option value='<?php echo $column['id']?>' <?php if($_GET['column_id']==$column['id']) echo 'selected';?>><?php echo $column['title']?></option>
			<?php }} ?>
			</select>&nbsp;
			总集：<select name="ispackage">
				<option value='' <?php if($_GET['ispackage']==0) echo 'selected';?>>全部</option>
				<option value='1' <?php if($_GET['ispackage']==1) echo 'selected';?>>总集</option>
			</select>
            排序字段：<select name="field">
				<option value='inputtime' <?php if($_GET['field']=='inputtime') echo 'selected';?>>导入时间</option>
				<option value='id' <?php if($_GET['field']=='id') echo 'selected';?>>ID</option>
			</select>&nbsp;
            排序方法：<select name="order">
				<option value='DESC' <?php if($_GET['order']=='DESC') echo 'selected';?>>降序</option>
				<option value='ASC' <?php if($_GET['order']=='ASC') echo 'selected';?>>升序</option>
			</select>&nbsp;
			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0" id="i_table_list_1">
        <tbody>
            <tr>
            <th style="width:50px;" align="right">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width="50" align="center">序号</th>
			<th width="68" align="center">导入时间</th>
			<th width="60" align="center">VID</th>
			<th width='128' align="center">名称</th>
			<th width='50' align="center">类型</th>
			<th width="30" align="center">状态</th>
			<th width="20" align="center">&nbsp;</th>
			<th width="118" align="center">操作</th>
            </tr>
	<?php if(is_array($data)){$i = 1+($page-1)* $perpage; foreach($data as $channel){?>  
	<tr>
	<td align="right"><input type="checkbox" name="asset_id" value="<?php echo $channel['asset_id']?>"  /></td>
	<td align="center"><?php echo $i++;?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="center"><?php echo $channel['asset_id']?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?></td>
	<td align="center"><?php echo offline_status($channel['offline_status'],$channel['published']);?></td>
	<td align="center"><a style="color:blue" href="javascript:view('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);"><?php echo '预览'?></a></td>
	<td align="center"><?php if($channel['offline_status'] == 0 && $channel['published'] == 1 ){?>
		<a style="color:blue" href="javascript:doconfirmurl(<?php echo $channel['id']?>, '申请下线')">申请下线</a> |	
		<a href="javascript:offline_content('<?php echo $channel['asset_id']?>');void(0);">在线链接</a> |
		<a href="javascript:offline_poster('<?php echo $channel['asset_id']?>');void(0);">在线图片</a>
		<?php if ($channel['hasComment'] == 1) {?> |
		<a href="javascript:showVideoComment('<?php echo $channel['id']?>');void(0);">网友评论</a>
			<?php }}?>
	</td>
	</tr>
	<?php }} ?>
	</div>
	<tr>
	<td align="right">全选<input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	选中项
	<select id="i_select_ckall">
		<option value="1">下线</option>
		<option value="2">删除</option>
		</select>
	<input type="button" onclick="doCKALL();" value="确定" />
	</td>
	</tr>
	</tbody>
    </table>
    <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function clickCKB(a){
	$('#i_table_list_1 tbody input[type=checkbox]').attr('checked',$(a).attr('checked')) ;
}
function doCKALL(){
	var doitem = $('#i_select_ckall').val() ;
	 var arr =[];    
     var str='';
     $("input[name=asset_id]:checked").each(function(){    
         arr.push({asset_id:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	}
    if(str!=''){
    	if(doitem == '2'){  //删除
			location.href ='?m=go3c&c=video&a=delete_allto&asset_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //下线 
			location.href ='?m=go3c&c=video&a=upline_pass_all&asset_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
    }
}
function view(id, title) {
    location.href ='?m=content&c=content&a=edit&catid=54&id='+id+'&pc_hash='+pc_hash+'&view=0'
	+'&goback='+BASE64.encode(location.search);
}
function doconfirmurl(id,title) {
    confirmurl('?m=go3c&c=video&a=offline_appy&id='+id+'&goback='+BASE64.encode(location.search),title);
}
function offline_content(asset_id) {
    location.href ='?m=go3c&c=video&a=offline_content_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function offline_poster(asset_id) {
    location.href ='?m=go3c&c=video&a=offline_poster_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function showVideoComment(id) {
	location.href = '?m=go3c&c=video&a=showVideoComment&id=' + encodeURIComponent(id) + '&pc_hash=' + pc_hash
	+ '&goback=' + BASE64.encode(location.search);
}
</script>
