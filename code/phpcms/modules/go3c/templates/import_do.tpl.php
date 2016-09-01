<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong>资源导入</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<td class="cn">
		<div id="i_doing_in" class="cn"></div>
		</td>
	</tr>
</table>
</div>

<script type="text/javascript">
<?php $tmp=array('imchannel'=>'频道','imepg'=>'EPG','imasset'=>'视频'); ?>
var doimL = <?php print json_encode($tmp); ?> ;
var doim = <?php print json_encode($doim); ?> ;

for(var k in doim) if(doim[k]) doim['i'+k] = 'import2db' ;

function ckDoFin(){
	var i = 0 ;
	var ifalse = 0 ;
	for(var k in doim){
		i++ ;
		if(!doim[k]) ifalse++ ;
	}
	if(ifalse == i) return true ;
	return false ;
}


function letDo(){
	if(ckDoFin()) return doOKEnd() ;
	for(var k in doim){
		if(!doim[k]) continue ;
		var sp = document.createElement('P') ;
		var spspan = document.createElement('SPAN') ;
		i$('i_doing_in').appendChild(sp) ;
		
		var ik = k ;
		var okFix = 'ImportDo_' ;
		var isurl =  '?m=go3c&c=import&a=doscript&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>&imlog_id=<?php print $imlog_id ;?>'+'&'+k+'='+encodeURIComponent(doim[k]) ;
		var ihtml = '正在从媒资服务器导入' + doimL[k] + '信息....';
		if(doim[k] == 'import2db'){
			ik = k.substr(1) ;
			okFix = 'ImportDoi_' ;
			ihtml = '正在导入' + doimL[ik] + '信息到数据库....';
			isurl = '?m=go3c&c=import&a=doiscript&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>&imlog_id=<?php print $imlog_id ;?>&mtype='+ik ;
		}
		
		sp.innerHTML = ihtml ;
		sp.appendChild(spspan) ;
		
		loadJson(isurl,function(){
			doim[k] = false ;
			if(window[okFix+ik] && window[okFix+ik].result && window[okFix+ik].result == 'ok'){
				spspan.innerHTML = '成功' ;
			}else{
				spspan.innerHTML = '失败' ;
			}
			if(ckDoFin()) return doOKEnd() ;
			letDo() ;
		}) ;
		break ;
	}
}


function doOKEnd(){
	alert('导入完毕!') ;
	window.location.href = '?m=go3c&c=imlog&a=show&pc_hash=<?php print $_SESSION['pc_hash'] ; ?>&id=<?php print $imlog_id ;?>' ;
}

letDo() ;
</script>
