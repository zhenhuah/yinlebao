<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<!--  
<div style="text-align:right;padding:0 50px 10px 0;">
  <input class="button" type="button" onclick="dopassall('确认批量通过下线申请？')" value="批量通过下线申请" />&nbsp;&nbsp;
  <input class="button" type="button" onclick="dorefuseall('确认批量拒绝下线申请？')" value="批量拒绝下线申请" />
</div>
-->
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="verify" name="c">
<input type="hidden" value="offline" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		    <a href="?m=go3c&c=verify&a=offline">全部数据</a>&nbsp;
			VID：<input name="asset_id" type="text" value="<?php if(isset($asset_id)) echo $asset_id;?>" class="input-text" />
			名称：<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
			   类型：<select name="column_id">
				<option value='' <?php if($_GET['column_id']==0) echo 'selected';?>>全部</option>
				 <?php {foreach($cms_column as $key=>$column){?>
           		 <option value='<?php echo $column['id']?>' <?php if($_GET['column_id']==$column['id']) echo 'selected';?>><?php echo $column['title']?></option>
				<?php }} ?>
			</select>
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
			<th width="50" align="center">VID</th>
			<th width='108' align="center">名称</th>
			<th width='50' align="center">类型</th>
			<th width='50' align="center"></th>
			<th width="50" align="center">内容编辑</th>
			<th width="68" align="center">操作</th>
            </tr>
	<?php if(is_array($data)){$i = 1+($page-1)*$pagesize;foreach($data as $channel){?>   
	<tr>
	<td align="right"><input type="checkbox" name="id" value="<?php echo $channel['id']?>"  /></td>
	<td align="center"><?php echo $i++?></td>
	<td align="center"><?php echo $channel['asset_id']?></td>
	<td align="center"><?php echo $channel['title']?></td>
	<td align="center"><?php echo columnid2name($channel['column_id'],$channel['ispackage'])?></td>
	<td align="center">
		<a style="color:blue" href="javascript:edit('<?php echo $channel['id']?>', '<?php echo safe_replace($channel['title'])?>');void(0);"><?php echo '详细'?></a>
	</td>
	<td align="center"><?php echo $channel['username'];?></td>
	<td align="center">
		<a style="color:green" href="javascript:dopass(<?php echo $channel['id']?>, '通过申请')">通过</a>
		&nbsp;&nbsp;
		<a style="color:red" href="javascript:dorefuse(<?php echo $channel['id']?>, '驳回申请')">拒绝</a>
		<input type="hidden" value="<?php echo $channel['id']?>" name="ids[]" />
	</td> 
	</tr>
	<?php }} ?>
	</div>
	<tr>
	<th style="width:50px;" align="right">全选<input type="checkbox" onclick="clickCKB(this);"  /></th>
	<td colspan="20">
	选中项
	<select id="i_select_ckall">
		<option value="1">通过</option>
		<option value="2">拒绝</option>
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
     $("input[name=id]:checked").each(function(){    
         arr.push({id:$(this).val()});
         str += $(this).val()+",";
     });
     if (str.length > 0) {
 	    //得到选中的checkbox值序列
    	 str = str.substring(0,str.length - 1);
 	} 
    if(str!=''){
		if(doitem == '2'){  //拒绝
			location.href ='?m=go3c&c=verify&a=delete_offline_allto&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}else if(doitem == '1'){  //通过
			location.href ='?m=go3c&c=verify&a=offline_to_all&id='+str+'&pc_hash='+pc_hash
	    	+'&goback='+BASE64.encode(location.search);
		}
    }else{
    	alert('你还没有选择任何内容！');
     }
}
function edit(id, title) {
    location.href ='?m=content&c=content&a=edit&catid=54&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dopass(id, title) {
    location.href ='?m=go3c&c=verify&a=offline_pass&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dorefuse(id, title) {
    location.href ='?m=go3c&c=verify&a=offline_refuse&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
function dopassall(title){
	if(confirm(title)){		
		document.myform.action="?m=go3c&c=verify&a=offline_pass_all&goback="+BASE64.encode(location.search);
		document.myform.submit(); 
	}
}
function dorefuseall(title){
	if(confirm(title)){		
		document.myform.action="?m=go3c&c=verify&a=offline_refuse_all&goback="+BASE64.encode(location.search);
		document.myform.submit(); 
	}
}
</script>
