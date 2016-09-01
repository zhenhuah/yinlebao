<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<style type="text/css">
* {
	margin:0;
	padding:0;
	list-style-type:none;
}
a, img {
	border:0;
}
body {
	font:12px/180% Tahoma, Geneva, sans-serif;
}
/* Carousel */
#carousel1 {
	width:960px;
	height:450px;
	overflow:hidden;
	position:relative;
	border-bottom:solid 1px #d8d9da;
	margin:-90px auto 0;
}
#carousel1 img {
	border:none;
	width:240px;
	height:180px;
	border:solid 1px #000;
}
#carousel1 #title-text {
	font-size:22px;
	margin:10px 20px 0 0;
	padding:0;
	text-align:right;
}
#carousel1 #alt-text {
	font-size:14px;
	margin:5px 20px 0 0;
	padding:0;
	text-align:right;
}
#carousel1 #user-c {
	padding:0;
	position:absolute;
	right:15px;
	bottom:10px;
}
#carousel1 .carouselLeft, #carousel1 .carouselRight {
	position:absolute;
	bottom:20px;
	width:29px;
	height:30px;
	overflow:hidden;
	cursor:pointer;
}
#carousel1 .carouselLeft {
	right:60px;
	background:url(http://www.go3c.tv:8060/go3ccms/statics/images/templatemo_slider_right.png) no-repeat;
}
#carousel1 .carouselRight {
	right:808px;
	background:url(http://www.go3c.tv:8060/go3ccms/statics/images/templatemo_slider_left.png) no-repeat;
}
</style>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>CloudCarousel.1.0.5.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.mousewheel.js"></script><!--鼠标滚动插件-->
<script type="text/javascript">
$(document).ready(function(){						   
	// 这初始化容器中指定的元素，在这种情况下，旋转木马.
	$("#carousel1").CloudCarousel({			
		xPos:450,
		yPos:110,
		buttonLeft: $('#but1'),
		buttonRight: $('#but2'),
		altBox: $("#alt-text"),
		titleBox: $("#title-text"),				
		FPS:30,
		reflHeight:86,
		reflGap:2,
		yRadius:40,
		autoRotateDelay: 1200,
		speed:0.2,
		mouseWheel:true,
		bringToFront:true
	});	
});
</script>
</head>
<body>
<div id="carousel1">
  <p id="title-text"></p>
  <p id="alt-text"></p>
  <?php if(is_array($data)){foreach($data as $client_version){?>
  <a href="<?php echo $client_version['url'];?>"  target="_blank"><img class="cloudcarousel"  src="<?php echo $client_version['url'];?>"/></a>   
  <?php }} ?>
  <div id="but1" class="carouselLeft"></div>
  <div id="but2" class="carouselRight"></div>
</div>
</body>
</html>