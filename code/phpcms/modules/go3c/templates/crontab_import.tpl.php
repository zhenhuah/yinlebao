<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" href="<?php echo CSS_PATH?>yzystyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JS_PATH?>yzyscript.js"></script>


<form action="" method="POST">


<div style="padding:10px 20px;width:500px;margin: 0 auto;">
<div class="explain-col"><strong>自动导入时间设置</strong></div>
<table cellspacing="0" cellpadding="0" border="0" class="table_form">
	<tr>
		<th>电视频道</th>
		<td>
			<select name="d[channel][cday]" id="i_channel_cday">
				<?php foreach($cdays as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php }?>
			</select>
			
			
			<select name="d[channel][hour]" id="i_channel_hour">
				<?php for($i=0;$i<24;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>点</option>
				<?php }?>
			</select>
			
			<select name="d[channel][minute]" id="i_channel_minute">
				<?php for($i=0;$i<60;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>分</option>
				<?php }?>
			</select>
			
		</td>
	</tr>
	
	
	<tr>
		<th>EPG</th>
		<td>
			
			<select name="d[epg][cday]" id="i_epg_cday">
				<?php foreach($cdays as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php }?>
			</select>
			
			
			<select name="d[epg][hour]" id="i_epg_hour">
				<?php for($i=0;$i<24;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>点</option>
				<?php }?>
			</select>
			
			<select name="d[epg][minute]" id="i_epg_minute">
				<?php for($i=0;$i<60;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>分</option>
				<?php }?>
			</select>
			
		</td>
	</tr>
	
	
	<tr>
		<th>视频</th>
		<td>
			
			<select name="d[asset][cday]" id="i_asset_cday">
				<?php foreach($cdays as $k=>$v){ ?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php }?>
			</select>
			
			
			<select name="d[asset][hour]" id="i_asset_hour">
				<?php for($i=0;$i<24;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>点</option>
				<?php }?>
			</select>
			
			<select name="d[asset][minute]" id="i_asset_minute">
				<?php for($i=0;$i<60;$i++){ ?>
				<option value="<?php echo $i;?>"><?php echo $i;?>分</option>
				<?php }?>
			</select>
			
		</td>
	</tr>
	
	<tr>
		<th>操作结束通知用户</th>
		<td>
			<table>
				<?php foreach($usernames as $k=>$v){ ?>
				<tr>
					<td>
					<label for="i_asset_us_<?php echo $k;?>">
					<input id="i_asset_us_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="us[]" />
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

