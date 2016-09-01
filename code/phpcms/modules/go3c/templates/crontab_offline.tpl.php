<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<form action="" method="POST">


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong>自动下线设置</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<th>自动下线检查时间</th>
		<td>
			<select name="d[cday]" id="i_off_video_cday">
				<?php foreach($cdays as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php }?>
			</select>
			
			
			<select name="d[hour]" id="i_off_video_hour">
				<?php for($i=0;$i<24;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>点</option>
				<?php }?>
			</select>
			
			<select name="d[minute]" id="i_off_video_minute">
				<?php for($i=0;$i<60;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>分</option>
				<?php }?>
			</select>
			
		</td>
	</tr>
	
	<tr>
		<th>自动下线检查提醒</th>
		<td>
			在 <input name="d[tinfo]" id="i_off_video_tinfo" value="<?php echo $tdays;?>" style="width:50px;" /> 天内将要下线的视频会被通知
		
		</td>
	</tr>
	
	
	<tr>
		<th>视频自动下线通知用户</th>
		<td>
			<table>
				<?php foreach($usernames as $k=>$v){ ?>
				<tr>
					<td>
					<label for="i_off_video_us_<?php echo $k;?>">
					<input id="i_off_video_us_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="aus[]" />
					<?php echo $k;?>
					</label>
					</td>
				</tr>
				<?php }?>
			</table>
		
		</td>
	</tr>
	
	
	<tr>
		<th>推荐位自动下线通知用户</th>
		<td>
			<table>
				<?php foreach($usernames as $k=>$v){ ?>
				<tr>
					<td>
					<label for="i_off_position_video_us_<?php echo $k;?>">
					<input id="i_off_position_video_us_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="pus[]" />
					<?php echo $k;?>
					</label>
					</td>
				</tr>
				<?php }?>
			</table>
		
		</td>
	</tr>
	
<script type="text/javascript">

<?php foreach($d as $k=>$vs){ ?>
	<?php foreach($vs as $ik=>$iv){ ?>
	<?php if($ik == 'us'){; ?>
		<?php $iv=explode(',',$iv);?>
		<?php foreach($iv as $iik=>$iiv){ ?>
	
			if(document.getElementById('i_<?php echo $k;?>_<?php echo $ik;?>_<?php echo $iiv;?>')) document.getElementById('i_<?php echo $k;?>_<?php echo $ik;?>_<?php echo $iiv;?>').checked = true ;
		<?php } ?>
		
	<?php }else{ ?>
			if(document.getElementById('i_<?php echo $k;?>_<?php echo $ik;?>')) document.getElementById('i_<?php echo $k;?>_<?php echo $ik;?>').value = '<?php echo $iv;?>' ;
	
	<?php } ?>
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

