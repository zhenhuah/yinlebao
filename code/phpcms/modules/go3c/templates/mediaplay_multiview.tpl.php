<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>

<div>
<table>
	<?php
  if(is_array($cms_video_content)) {$i=0;
	  	foreach($cms_video_content as $key => $value) {$i++;
	  ?>
	<?php if($i==1 || $i==4) echo "<tr>";?>
	<td>
	<div style="margin:10px;">
    		<object classid="clsid:9be31822-fdad-461b-ad51-be1d1c159921"
					width="340"
					height="280"
					id="vlc_<?='v'.$i?>"
					events="true">
			<param name="mrl" value="<?=$value['path']?>" />

			<param name="showdisplay" value="true" />
			<param name="autoloop" value="false" />
			<param name="autoplay" value="false" />
			<param name="volume" value="50" />
			<param name="starttime" value="0" />
			<embed pluginspage="http://www.videolan.org"
						 type="application/x-vlc-plugin"
						 version="videolan.vlcplugin.2"
						 width="340"
						 height="280"
						 name="vlc_<?='v'.$i?>"
						 autoplay="no" 
						 loop="no" 
						 hidden="no"
						 target="<?=$value['path']?>"
						 style="margin:10px;border:3px gray solid;"
			>
			</embed>
			</object>
			<br/>
			<span>清晰度<?=$value['clarity']?></span>&nbsp;&nbsp;<input type="button" name="<?='v'.$i?>" value="同时播放"  onclick="playAll()"/>&nbsp;&nbsp;<input type="button" value="单屏播放"  onclick="playSingle('<?=$assetid?>','<?=$value['id']?>')"/>
     </div>
</td>

<?php if($i==3 || $i==5) echo "</tr>";?>
<?php }}?>
</table>
</div>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function playSingle(assetid,id){
	location.href="?m=go3c&c=mediaplay&a=mediaplay_view&assetid="+assetid+"&id="+id+'&pc_hash='+pc_hash;
}

/*
* 得到VLC flash节点
*/
function getVLC(name)
{
	if (document[name])	//ff
	{
		return document[name];

    }else if (window.document[name]){//ie
            
		return window.document[name];

    }else if (navigator.appName.indexOf("Microsoft Internet")==-1){//other
        if (document.embeds && document.embeds[name])
		{
			return document.embeds[name];
		}else{
			return document.getElementById(name);
		}
     }else{
		 return document.getElementById(name);
     }
}

/*
* 同一页多播操作
* param mrl 多媒体资源URL
* param locateName 要操作的VLC FLASH 结点名
*/
function morePlayType(mrl,locateName)
{
//morePlayType('<?=$value['path']?>','<?='v'.$i?>')
	if ((mrl != '') && (locateName != ''))
	{
		var runPlayUrl = inPlayUrl(mrl,locateName);

		if (runPlayUrl != '')
		{			
			var vlc = getVLC('vlc_' + locateName);
			//alert(vlc);

			itemId=vlc.playlist.add(mrl);

			vlc.playlist.playItem(itemId);	
		}
	}
}

function playAll(){
	var urls = new Array();
	<?php
  	if(is_array($cms_video_content)) {
	  	foreach($cms_video_content as $key => $value) {?>
	urls.push("<?=$value['path']?>");	
	<?php }}?>

	for(i=0;i<urls.length;i++){
		morePlayType(urls[i],"v"+(i+1));	
	}
}

var glocateName = ''
/*
* 解析可用URL类型 【请与上一个JS进行合并，最终该方法只返回一个可用的URL播放地址】
*/
function inPlayUrl(playUrl,locateName)
{
	//playlive.js?		有防盗链判断
	//playlive.js?uuid	有防盗链判断且是直播
	//playvod.js?url	有防盗链判断且是点播
	//无上面的特殊类型为直接传给VLC直接播放类型

	if (playUrl != '')	//视频数据 得到当前视频该类型的数据库播放地址数据
	{
		if(playUrl.indexOf("playlive.js?") > 0 )	//有防盗链 
		{
			if(playUrl.indexOf("playlive.js?uuid=") > 0 )//直播判断
			{
				glocateName = locateName;
				$.get("phpcms/libs/proxy.php?url="+BASE64.encode(playUrl), function(data){
				  //alert("Data Loaded: " + data);
					eval(data);
				});
				return '';
			}else{
				var proccessUrl = '';
			}
		}else if (playUrl.indexOf("playvod.js?") > 0){//有防盗链

			if(playUrl.indexOf("playvod.js?url=") > 0 )//点播判断
			{
				glocateName = locateName;
				$.get("phpcms/libs/proxy.php?url="+BASE64.encode(playUrl), function(data){
				  //alert("Data Loaded: " + data);
					eval(data);
				});
				return '';
			}else{
				var proccessUrl = '';
			}
		}else{	//无盗链处理，直接给VLC
			var proccessUrl = playUrl;
		}
	}

	//执行播放
	if (proccessUrl != '')	//分析 得到真实可用的URL 真实有效，进行播放
	{
		return proccessUrl;

	}else{	//无效URL数据
		return '';
	}
}

/*
* 视频切换并播放
*/
function goOn(mrl)
{
	if (mrl != '' && glocateName!='')
	{		
		var vlc = getVLC("vlc_"+glocateName);
alert("vlc_"+glocateName+":"+mrl)
		itemId=vlc.playlist.add(mrl);
		vlc.playlist.playItem(itemId);

		glocateName = '';
		//时间UI
		//vlc.input.state：当前状态（空闲/关闭= 0，开幕= 1，缓冲= 2，打开= 3，暂停= 4，停止= 5，错误= 6）
		//document.getElementById("btn_stop").disabled = false;
	}
}
</script>
