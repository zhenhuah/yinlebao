<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<link href="<?php echo CSS_PATH?>jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.treetable.js"></script>
<script type="text/javascript" src="statics/js/base64.js"></script>
<div class="table-list" id="load_priv">
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
<script type="text/javascript">
//添加信息表单验证
function addcolumn()
{
	var id = $.trim($('#id').val());
	if (id != '')
	{
		$('#ad_id').hide();
	}else{
		$('#ad_id').show();
		return false;
	}
	if(!(/^[0-9]*$/g.test(id))){
        alert("请输入数字!");
    }
	var name = $.trim($('#name').val());
	if (name != '')
	{
		$('#ad_name').hide();
	}else{
		$('#ad_name').show();
		return false;
	}
	return true;
}
</script>
<form name="myform" id="myform" action="?m=go3c&c=config&a=editcolumndo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" onSubmit="return addcolumn();">
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<tr>
<th width="60">栏目ID</th>
<td>
<input type="text" value="<?php echo $limitInfo['id'];?>" name="id" id="id" size="25" >
<span id="ad_id" style="display:none"><font color="red">请填写栏目ID</font>
</td>
</tr>
<tr>
<th width="60">栏目名称</th>
<td>
<input type="text" value="<?php echo $limitInfo['title'];?>" name="name" id="name" size="25" >
<span id="ad_name" style="display:none"><font color="red">请填写名称</font>
</td>
</tr>
<tr>
<th width="80">区域选择:</th>
<td>
<table width="100%" cellspacing="0" id="dnd-example">
<tbody>
<?php echo $categorys;?>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<div class="btn"><input type="submit"  class="button" name="dosubmit" id="dosubmit" value="<?php echo L('submit');?>" /></div>
</form>
</div>
</body>
</html>
