<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<form action="" method="POST">


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong>自动发布设置</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<th>自动发布时间</th>
		<td>
			<select name="d[cday]" id="i_cday">
				<?php foreach($cdays as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php }?>
			</select>
			
			
			<select name="d[hour]" id="i_hour">
				<?php for($i=0;$i<24;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>点</option>
				<?php }?>
			</select>
			
			<select name="d[minute]" id="i_minute">
				<?php for($i=0;$i<60;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>分</option>
				<?php }?>
			</select>
			
		</td>
	</tr>
	
	<tr>
		<th>自动发布项目</th>
		<td>
			<table>
				<?php foreach($atlist as $k=>$v){ ?>
				<tr>
					<td>
					<label for="i_atlist_<?php echo $k;?>">
					<input id="i_atlist_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="atlist[]" />
					<?php echo $v;?>
					</label>
					</td>
				</tr>
				<?php }?>
			</table>
		
		</td>
	</tr>
	
	
	<tr>
		<th>操作结束通知用户</th>
		<td>
			<table>
				<?php foreach($usernames as $k=>$v){ ?>
				<tr>
					<td>
					<label for="i_us_<?php echo $k;?>">
					<input id="i_us_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="us[]" />
					<?php echo $k;?>
					</label>
					</td>
				</tr>
				<?php }?>
			</table>
		
		</td>
	</tr>
	
<script type="text/javascript">

	<?php foreach($dd as $ik=>$iv){ ?>
	<?php if($ik == 'us'){; ?>
		<?php $iv=explode(',',$iv);?>
		<?php foreach($iv as $iik=>$iiv){ ?>
	
			if(document.getElementById('i_us_<?php echo $iiv;?>')) document.getElementById('i_us_<?php echo $iiv;?>').checked = true ;
		<?php } ?>
		
	<?php }else if($ik == 'tinfo'){ ?>
		<?php $iv=explode(',',$iv);?>
		<?php foreach($iv as $iik=>$iiv){ ?>
			if(document.getElementById('i_atlist_<?php echo $iiv;?>')) document.getElementById('i_atlist_<?php echo $iiv;?>').checked = true ;
		
		<?php } ?>
		
	<?php }else{ ?>
		if(document.getElementById('i_<?php echo $ik;?>')) document.getElementById('i_<?php echo $ik;?>').value = '<?php echo $iv;?>' ;
	<?php } ?>
	
	<?php } ?>

</script>
	
	<tr>
		<td colspan="10" style="text-align:center;">
			<input class="button" type="submit" value="设定" name="submit" />
		</td>
	</tr>
</table>
</div>
</form>

