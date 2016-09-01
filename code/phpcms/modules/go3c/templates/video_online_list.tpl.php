<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<div class="content-menu ib-a blue line-x">
	<a class="add fb" href="javascript:addnew()" ><em><?php echo L('add_video');?></em></a>
</div>
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="online" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=video&a=online"><?php echo L('all_data');?></a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />&nbsp;
			<?php echo L('name');?>：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />&nbsp;
           <?php echo L('status');?>：<select name="online_status">
				<option value='' <?php if($_GET['online_status']==0) echo 'selected';?>><?php echo L('all');?></option>
				<option value='1' <?php if($_GET['online_status']==1) echo 'selected';?>><?php echo L('import');?></option>
				<option value='2' <?php if($_GET['online_status']==2) echo 'selected';?>><?php echo L('editing');?></option>
				<option value='3' <?php if($_GET['online_status']==3) echo 'selected';?>><?php echo L('not_pass');?></option>
				<option value='10' <?php if($_GET['online_status']==10) echo 'selected';?>><?php echo L('submitt');?></option>
				<option value='11' <?php if($_GET['online_status']==11) echo 'selected';?>><?php echo L('onsubmit');?></option>
				<option value='12' <?php if($_GET['online_status']==12) echo 'selected';?>><?php echo L('notsubmit');?></option>
				<option value='20' <?php if($_GET['online_status']==20) echo 'selected';?>><?php echo L('delete');?></option>
			</select>&nbsp;
            <?php echo L('type');?>：<select name="column_id">
            <option value='' <?php if($_GET['column_id']==0) echo 'selected';?>><?php echo L('all');?></option>
            <?php {foreach($cms_column as $key=>$column){?>
           		 <option value='<?php echo $column['id']?>' <?php if($_GET['column_id']==$column['id']) echo 'selected';?>><?php echo $column['title']?></option>
			<?php }} ?>
			</select>
			<?php echo L('collections');?>：<select name="ispackage">
				<option value='' <?php if($_GET['ispackage']==0) echo 'selected';?>><?php echo L('all');?></option>
				<option value='1' <?php if($_GET['ispackage']==1) echo 'selected';?>><?php echo L('collections');?></option>
			</select>
            <?php echo L('Sort field');?>：<select name="field">
				<option value='inputtime' <?php if($_GET['field']=='inputtime') echo 'selected';?>><?php echo L('import_time');?></option>
				<option value='id' <?php if($_GET['field']=='id') echo 'selected';?>>ID</option>
			</select>&nbsp;
           <?php echo L('ranking');?>：<select name="order">
				<option value='DESC' <?php if($_GET['order']=='DESC') echo 'selected';?>><?php echo L('descending');?></option>
				<option value='ASC' <?php if($_GET['order']=='ASC') echo 'selected';?>><?php echo L('ascending');?></option>
			</select>&nbsp;
			<?php echo L('each page');?>：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
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
            <th style="width:50px;" align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);"  /></th>
			<th width="20" align="center">ID</th>
			<th width="30" align="left"><?php echo L('import_time');?></th>
			<th width="60" align="left">VID</th>
			<th width='130' align="left"><?php echo L('name');?></th>
			<th width='50' align="left"><?php echo L('type');?></th>
			<th width="60" align="left"><?php echo L('status');?></th>
			<th width="30" align="left"><?php echo L('is_pic');?></th>
			<th width="120" align="center"><?php echo L('operation');?></th>
            </tr>
	<?php if(is_array($data)){$i =1+($page-1)*$pagesize; foreach($data as $channel){?>   
	<tr>
	<td align="right"><input type="checkbox" name="asset_id" value="<?php echo $channel['asset_id']?>"  /></td>
	<td align="center"><?php echo $channel['id']?></td>
	<td align="left"><?php echo date('Y-m-d H:i:s', $channel['inputtime'])?></td>
	<td align="left"><?php echo $channel['asset_id']?></td>
	<td align="left"><?php echo $channel['title']?></td>
	<td align="left"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?></td>
	<td align="left"><?php echo online_status($channel['online_status']);?></td>
	<td align="left"><a style="color:red"><?php echo videopic($channel['pic'])?></a></td>
	<td align="left">
		<a href="javascript:video_content('<?php echo $channel['asset_id']?>');void(0);"><?php echo L('vlink');?></a> |
		<a href="javascript:video_poster('<?php echo $channel['asset_id']?>');void(0);"><?php echo L('posters');?></a> |
		<a href="javascript:video_sub('<?php echo $channel['asset_id']?>');void(0);"><?php echo L('subtitle');?></a> |
		<a href="javascript:video_trailer('<?php echo $channel['asset_id']?>');void(0);"><?php echo L('trailer');?></a> |
		<!--
		<a href="javascript:video_content_add('<?php echo $channel['asset_id']?>');void(0);">加链</a> |
		<a href="javascript:video_poster_add('<?php echo $channel['asset_id']?>');void(0);">加图</a> |
		-->
		<?php if($channel['online_status'] != 11) {?>
		<?php    if($channel['online_status'] != 10){?>
		<a style="color:blue" href="javascript:edit('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);"><?php echo L('edit')?></a> |		
		<?php    if($channel['online_status'] != 3){?>
		<a style="color:green" href="javascript:dopass(<?php echo $channel['id']?>, '<?php echo L('application');?>')"><?php echo L('application');?></a> | 
		<a style="color:red" title="置为编辑未通过状态" href="javascript:dorefuse(<?php echo $channel['id']?>, '<?php echo L('data error');?>')"><?php echo L('data error');?></a> |
		<a style="color:blue" title="删除数据" href="javascript:deletevideo('<?php echo $channel['asset_id']?>', '<?php echo L('delete data');?>')"><?php echo L('delete data');?></a>|
		<?php    if($channel['column_id']== 5){?>
		<a style="color:green" title="豆瓣抓取" href="javascript:douban('<?php echo $channel['title']?>', '<?php echo L('douban');?>')"><?php echo L('douban');?></a>
		<?php }}}}?>
	</td>
	</tr>
	<?php }} ?>
	</div>
	<tr>
	<td align="right"><?php echo L('check_all');?><input type="checkbox" onclick="clickCKB(this);" value=""  /></td>
	<td colspan="20">
	<?php echo L('selected item');?>
	<select id="i_select_ckall">
		<option value="1"><?php echo L('examine');?></option>
		<option value="2"><?php echo L('del');?></option>
		<option value="3"><?php echo L('error');?></option>
		</select>
	<input type="button" onclick="doCKALL();" value="<?php echo L('determine');?>" />
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
		}else if(doitem == '1'){  //审核 
			location.href ='?m=go3c&c=video&a=online_pass_all&asset_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '3'){  //设置为数据有误
			location.href ='?m=go3c&c=video&a=online_error&asset_id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);	
			}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function addnew(){
	location.href ='?m=content&c=content&a=add&catid=54&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function edit(id, title) {
    location.href ='?m=content&c=content&a=edit&catid=54&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function dopass(id, title) {
    location.href ='?m=go3c&c=video&a=online_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function deletevideo(asset_id) {
    location.href ='?m=go3c&c=video&a=delete_video&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=video&a=online_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function video_content(asset_id) {
    location.href ='?m=go3c&c=video&a=video_content_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function video_content_add(asset_id) {
	location.href ='?m=content&c=content&a=add&catid=64&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}

function video_poster(asset_id) {
    location.href ='?m=go3c&c=video&a=video_poster_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function video_sub(asset_id) {
    location.href ='?m=go3c&c=video&a=video_sub_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function video_poster_add(asset_id) {
	location.href ='?m=content&c=content&a=add&catid=65&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function video_trailer(asset_id) {
    location.href ='?m=go3c&c=video&a=video_trailer_list&asset_id='+encodeURIComponent(asset_id)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function douban(title) {
    location.href ='?m=go3c&c=video&a=douban&title='+encodeURIComponent(title)+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
