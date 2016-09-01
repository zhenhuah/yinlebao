<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<script  type="text/javascript">
	var playurl='';
		<?php
		  		foreach($cms_video as $key => $value) {
		  ?>
			playurl = "<?=$value['imgUrl']?>"
		<?php }?>
</script>
<!--div><?php print_r($cms_video) ?></div-->
    <div class="col-auto">
    	<div class="col-1">
        	<div class="content pad-6">
<table  cellspacing="0">
<tr><td>
	<table cellspacing="0" class="table_form">
	<tbody>	
	<tr>
      <th width="20">名称:
	  </th>
      <td width="100"><?=$cms_video['position']?></td>
    </tr>
    <tr>
      <td>
	<input type="hidden" name="playItem" id="playItem" value="0" />
	<input type="hidden" name="newRequestTypePlay" id="newRequestTypePlay" value="" />
	</td>
    </tr>
</tbody>
</table>
</td>
<td width='50px'></td>
<td>
<div>
	<div>
			<object classid="clsid:9be31822-fdad-461b-ad51-be1d1c159921"
					width="640"
					height="480"
					id="vlc"
					events="true">
			<param name="mrl" value="" />
			<param name="showdisplay" value="true" />
			<param name="autoloop" value="false" />
			<param name="autoplay" value="false" />
			<param name="volume" value="50" />
			<param name="starttime" value="0" />
			<embed pluginspage="http://www.videolan.org"
						 type="application/x-vlc-plugin"
						 version="videolan.vlcplugin.2"
						 width="640"
						 height="480"
						 name="vlc"
						 autoplay="no" 
						 loop="no" 
						 hidden="no"
			>
			</embed>
			</object>
	</div>

	<div>	
		<input type="button" id="pause_or_play" value="暂停" onClick='doPausePlay()' />
		
		<input type="button" value="前进" onclick='doSeekForward()' />
		<input type="button" value="后退" onclick='doSeekBackward()' />
		<span title="播放时间" id="videoStartTime">00:00:00</span>/<span title="播放总时间" id="videoAllTime">00:00:00</span>
		<input type="button" value="加音" onclick='doVolumeUp()' />
		<input type="button" value="减音" onclick='doVolumeDown()' />
		<input type="button" value="静音" onclick='doMute()' />
		<input type="button" value="全屏" onclick='doFullscreen()' />
	</div>
</div>
</td>
<td width='50px'></td>
<td>
<td>
<!--div id="right">
	<form name="myfrom" action="" method="post"> 
		<input type="button" value="编辑" id="pause_edit_desc" onclick='pauseEditDesc()' />
		<input type="button" value="保存" id="play_save_desc" onclick='playSaveDesc()' disabled/><br />
		备注开始时间点:<br /><input type="text" name="videoStartTime" id="videoStartTime" value="" /><br />
		备注结束时间点:<br /><input type="text" name="videoEndTime" id="videoEndTime" value="" /><br />

		时间点备注:<br/>
		<textarea name="videoDesc" id="videoDesc">
		</textarea>
	</form>
</div-->
</td>
</tr>
</table>
</div>
</div>

</div>
</body>
</html>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript" src="statics/js/VideoCore.js"></script>
<script  type="text/javascript">

function playTimeCount()
{
	var vlc = getVLC('vlc');

	var playStatus = vlc.input.state;
	if ((playStatus == '3') || (playStatus == '4'))
	{
		c = vlc.input.time;
		if (c > 0)
		{
			var c = parseInt(c / 1000);// 秒数
		}

		var playLength = vlc.input.length;

		if(playLength > 0)
		{
			document.getElementById('videoAllTime').innerHTML = setVideoTime(vlc.input.length);
			//$('#videoAllTime').html(setVideoTime(vlc.input.length));
		}
	}else{
		c = 0;
	}
	
	var temptextmin=document.getElementById('videoStartTime');

	hour = parseInt(c / 3600);// 小时数

	if (hour < 10)	//小时处理
	{
		hour = '0' +'' + hour;
	}


	min = parseInt(c / 60);// 分钟数

	if(min >= 60)
	{
	    min = min%60
	}

	if (min < 10)	//分钟处理
	{
		min = '0' +'' + min;
	}

	lastsecs = c % 60;

	if(lastsecs < 10)//秒钟处理
	{
		lastsecs = '0' +'' + lastsecs;
	}
	
	temptextmin.innerHTML = hour + ":" + min + ":" + lastsecs;	//显示

	//c = c+1;	//时间

	temp = setTimeout("playTimeCount()",1000);

}

playRouteUrl(playurl);
playTimeCount();
</script>

