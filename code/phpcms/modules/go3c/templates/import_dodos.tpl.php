<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong>导入进度</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<td class="cn">
		<div id="i_doing_in" class="cn">正在进行导入任务，请稍等</div>
		</td>
	</tr>
</table>
</div>

<script type="text/javascript">
var id = '<?php print $id;?>' ;
var pp = 0 ;
var ppi = 0 ;
var SIV = setInterval(function(){
	jQuery.post('?m=go3c&c=import&a=doimportstauts&pc_hash=<?php echo $_SESSION['pc_hash'];?>',{id:id},function(t){
		if(t.status == '9'){
			$("#i_doing_in").prepend('<p>当前进度：100%</p>');
			clearInterval(SIV) ;
			setTimeout(function(){ 
				alert('导入成功！') ;
				window.location.href = '?m=go3c&c=imlog&a=show&pc_hash=<?php echo $_SESSION['pc_hash'];?>&id=' + t.id  ;
			
			},1000) ;
		}else if(t.status == '4'){
			alert('导入失败！请重试！') ;
			clearInterval(SIV) ;
		}else if(t.status == '1'){
			if(pp < 30) pp += 3 ;
			else if(pp < 60) pp += 2 ;
			else if(pp < 80) pp++ ;
			
			if(pp >= 80){
				ppi++ ;
				if(ppi == 10){
					pp++ ;
					if(pp >= 98) pp=98 ; 
					ppi = 0 ;
					$("#i_doing_in").prepend('<p>当前进度：'+pp+'%，请等待！请不要关闭浏览器或者进行其他操作！</p>');
				}
			}
			
			

			if(pp < 80){
				$("#i_doing_in").prepend('<p>当前进度：'+pp+'%，请不要关闭浏览器或者进行其他操作！</p>');
			}
		}
	},'json') ;
},2000) ;

</script>
