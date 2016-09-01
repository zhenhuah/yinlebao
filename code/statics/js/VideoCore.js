var itemId = 0;
var seek_time = 1000 * 20;	//前进或后退的间距变化量
var volume_number = 10;		//音量

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
* 视频切换并播放
*/
function goPlayUrl(mrl)
{
	if (mrl != '')
	{		
		var vlc = getVLC("vlc");

		itemId=vlc.playlist.add(mrl);

		vlc.playlist.playItem(itemId);

		//vlc.input.state：当前状态（空闲/关闭= 0，开幕= 1，缓冲= 2，打开= 3，暂停= 4，停止= 5，错误= 6）
		//document.getElementById("btn_stop").disabled = false;
	}
}

/*
* 解析URL类型,进行播放
*/

function playRouteUrl(playUrl)
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
				$.get("phpcms/libs/proxy.php?url="+BASE64.encode(playUrl), function(data){
				  //alert("Data Loaded: " + data);
					eval(data);
				});
			}else{
				var proccessUrl = '';
			}
		}else if (playUrl.indexOf("playvod.js?") > 0){//有防盗链

			if(playUrl.indexOf("playvod.js?url=") > 0 )//点播判断
			{
				$.get("phpcms/libs/proxy.php?url="+BASE64.encode(playUrl), function(data){
				  //alert("Data Loaded: " + data);
					eval(data);
				});

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
		goOn(proccessUrl);

	}else{	//无效URL数据
		return false;
	}
	//return proccessUrl;
}


function goOn(proccessUrl){
		//document.getElementById("newRequestTypePlay").value = '';//请空原数据

	document.getElementById("playItem").value = proccessUrl;

	document.getElementById("newRequestTypePlay").value = proccessUrl;
		
	goPlayUrl(proccessUrl);
}

/*
* 暂停或播放
*/
function doPausePlay()
{
	var vlc = getVLC("vlc");

	vlc.playlist.togglePause();
	//viewPlayTime();
	//设置UI变化 pause_or_play
	var currentValue = document.getElementById("pause_or_play").value;

	if (currentValue == '播放')
	{
		document.getElementById("pause_or_play").value = '暂停';
	}else{
		document.getElementById("pause_or_play").value = '播放';
	}
}


//加声音
function doVolumeUp()
{
	var vlc = getVLC("vlc");

	if((vlc.audio.volume + volume_number) <= 200)
	{
		vlc.audio.volume += volume_number;   
	}else{
		vlc.audio.volume = 200;   
	}
}


//减声音
function doVolumeDown()
{
	var vlc = getVLC("vlc");

	if((vlc.audio.volume - volume_number)>= 0)
	{
		vlc.audio.volume -= volume_number;       
	}else{
		vlc.audio.volume = 0;
	}
}

/*
* 显示当前时间
*/
function viewPlayTime()
{	
	var vlc = getVLC("vlc");

	var playLength = vlc.input.length;
	if(playLength > 0)
	{
		//alert(vlc.input.state);
		$('#videoAllTime').html(setVideoTime(vlc.input.length));
	}
}

/*
* 转化时间
*/
function setVideoTime(msd)
{
	var time = parseFloat(msd) /1000;
	
	if ((time != null) && (time != ''))
	{
		if ((time >60) && (time <60*60))
		{
			/*
			time = parseInt(time /60.0) +"分钟"+ parseInt((parseFloat(time /60.0) -
            parseInt(time /60.0)) *60) +"秒";
			*/
			time = parseInt(time /60.0) +":"+ parseInt((parseFloat(time /60.0) -
            parseInt(time /60.0)) *60);
        }else if ((time >=60*60) && (time <60*60*24)){
			/*
			time = parseInt(time /3600.0) +"小时"+ parseInt((parseFloat(time /3600.0) -
            parseInt(time /3600.0)) *60) +"分钟"+
            parseInt((parseFloat((parseFloat(time /3600.0) - parseInt(time /3600.0)) *60) -
            parseInt((parseFloat(time /3600.0) - parseInt(time /3600.0)) *60)) *60) +"秒";
			*/
			time = parseInt(time /3600.0) +":"+ parseInt((parseFloat(time /3600.0) -
            parseInt(time /3600.0)) *60) +":"+
            parseInt((parseFloat((parseFloat(time /3600.0) - parseInt(time /3600.0)) *60) -
            parseInt((parseFloat(time /3600.0) - parseInt(time /3600.0)) *60)) *60);
        }else {
           //time = parseInt(time) +"秒";
		   time = "00" + ":" + parseInt(time);
        }
     }else{
        time = "00:00";	// 分:秒/时:分
     }
     
	 return time;
}

/*
* 是否静音
*/
function doMute()
{
	var vlc = getVLC("vlc");
	vlc.audio.toggleMute();   
}


//快进
function doSeekForward()
{         
	var vlc = getVLC("vlc");

	if((vlc.input.time + seek_time) < vlc.input.length )
	{
		vlc.input.time = vlc.input.time + seek_time;   
	}else{
		vlc.input.time = 0;       
	}
}

//后退
function doSeekBackward()
{
	var vlc = getVLC("vlc");

	if((vlc.input.time - seek_time)>= 0)
	{
		vlc.input.time -= seek_time;       
	}else{
		vlc.input.time = 0       
	}
}

/*
* 全屏
*/
function doFullscreen()
{
	var vlc = getVLC("vlc");
	vlc.video.toggleFullscreen();   
}

//编辑
function pauseEditDesc()
{
	doPausePlay();		//暂停
	var vlc = getVLC("vlc");
	var currentPlayTime = setVideoTime(vlc.input.time);

	$('#videoStartTime').val(currentPlayTime);

	alert(currentPlayTime)
	document.getElementById("pause_edit_desc").disabled = true; //禁用当前按扭效果
	document.getElementById("play_save_desc").disabled = false;	//启用保存UI效果
}

//保存
function playSaveDesc()
{
	document.getElementById("pause_edit_desc").disabled = false;	//启用编辑UI效果
	document.getElementById("play_save_desc").disabled = true;	//禁用当前保存UI效果
	var vlc = getVLC("vlc");
	
	var addTime = 1000 * 30;	//加上三十秒的时间

	var currentPlayTime = vlc.input.time;	//

	//说明：currentEndTime是你们最终要存放的毫秒数时间或使用currentPlayTime
	var currentEndTime = setVideoTime(currentPlayTime+addTime);

	/*
	* 请在这里存放当前备注内容和时间
	*/

	$('#videoEndTime').val(currentEndTime);

	alert("备注内容:"+ $('#videoDesc').val());
	
	doPausePlay();		//播放

	alert('保存成功')
	
}

/////////////////////////////////////////////////////////////////////////////////////////
//多屏传值这样调用播放，请把页面都加载完毕了，再调用，因为执行顺序
//playRouteUrl("http://people.videolan.org/~dionoea/vlc-plugin-demo/streams/sw_h264.asf");
