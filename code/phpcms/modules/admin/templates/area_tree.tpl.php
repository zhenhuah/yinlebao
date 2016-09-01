<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
  function checknode(obj)
  {
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) 
              {
                  chk.eq(i).attr("checked",true);
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")==true) {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",true);
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }
</script>
<div class="table-list" id="load_priv">
<table width="100%" cellspacing="0">
	<thead>
	<tr>
	<th class="text-l cu-span" style='padding-left:30px;'><span onClick="javascript:$('input[name=menuid[]]').attr('checked', true)"><?php echo L('selected_all');?></span>/<span onClick="javascript:$('input[name=menuid[]]').attr('checked', false)"><?php echo L('cancel');?></span></th>
	</tr>
	</thead>
</table>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
</div>
<div class="table-list">
<form name="myform" action="" method="GET">
<input type="hidden" value="admin" name="m">
<input type="hidden" value="area" name="c">
<input type="hidden" value="index" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
			区域名称：<input name="name" type="text" value="<?php if(isset($name)) echo $name;?>" class="input-text" />&nbsp;
			父区域名称：<input name="fname" type="text" value="<?php if(isset($fname)) echo $fname;?>" class="input-text" />&nbsp;
   			每页显示：<input name="perpage" type="text" value="<?php if(isset($perpage)) echo $perpage;?>" class="input-text" size="3" />&nbsp;
   			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</td>
		</tr>
    </tbody>
</table>
		<div class="content-menu ib-a blue line-x">
		<a class="add fb" href="javascript:video_area_add()"><em>添加区域</em></a>
		</div>
<div class="table-list">
          <table width="100%" cellspacing="0" class="table_form" style="margin-top:20px;">
          <tr>
            <td width="50">区域id</td>
            <td width="80">区域名称</td>
            <td width="50">父区域id</td>
            <td width="80">父区域名称</td>
            <td width="80">操作</td>
          </tr>
		  <?php
		  if(is_array($data)) {
		  	//print_r($cms_video_poster);
		  	foreach($data as $key => $value1) {
		  ?>
          <tr>
            <td><?=$value1['id']?></td>
            <td><?=$value1['name']?></td>
            <td><?=$value1['parentid']?></td>
            <td><?=$value1['fname']?></td>
            <td><a href="javascript:video_area_edit('<?php echo $value1['id']?>');void(0);">编辑</a>|
			<a href="javascript:video_area_delete('<?php echo $value1['id']?>');void(0);">删除</a></td>
          </tr>
          <?php }}?>
        </table>
        <div id="pages"><?php echo $this->cms_area->pages;?></div>
</form>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function video_area_add()
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=area&a=addarea&pc_hash='+pc_hash,
		success: function(msg){
		art.dialog({
				content: msg,
				title:'添加区域',
				id:'viewOnlyDiv',
				lock:true,
				width:'350'
			});
		}
	});
}
function video_area_edit(id)
{
	$.ajax({
		type: "GET",
		url: 'index.php?m=admin&c=area&a=editarea&id='+ id +'&pc_hash='+pc_hash,
		success: function(msg){
		art.dialog({
				content: msg,
				title:'修改区域',
				id:'viewOnlyDiv',
				lock:true,
				width:'350'
			});
		}
	});
}
function video_area_delete(id) {
    location.href ='?m=admin&c=area&a=delete_area&id='+id+'&pc_hash='+pc_hash
	+'&goback='+BASE64.encode(location.search);
}
</script>
</body>
</html>
