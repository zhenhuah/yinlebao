<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform" action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="boot" name="c">
<input type="hidden" value="showview" name="a">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
 <table width="750" cellspacing="0">
         <tbody>
        <?php if(is_array($data)){$i =1+($page-1)*$pagesize; foreach($data as $v){?>  
         <tr>
         <td align="center">名称:<?php echo $v['position']?></td>&nbsp;
        <td align="center"><?php if($v['ispic'] == '1'){?>
          <a href="<?php echo $v['imgUrl'];?>"  target="_blank"><img style="width:300px; border:solid 1px gray; padding:2px;" src="<?php echo $v['imgUrl'];?>"/></a>
         <?php  } else { ?>
		<a href="?m=go3c&c=mediaplay&a=task_play&adId=<?=$v['adId']?>" target="_blank"><?php echo $v['imgUrl']?></a>
		<?php } ?>
		</td>
         </tr>
         <?php }}else{echo "<tr><td align='center' colspan='7'>暂无数据</td></tr>";}?>
         </tbody>
    </table>
     <div id="pages"><?php echo $this->adverts_db->pages;?></div>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="hidden" name="mode" id="mode" value="showview" />
	<input type="hidden" name="spid" id="spid" value="<?php echo $spid;?>" />
	<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
	<input type="submit" class="button" name="dosubmit" id="dosubmit" value="提交" />&nbsp;
</div> 
</form>
</body>
</html>
