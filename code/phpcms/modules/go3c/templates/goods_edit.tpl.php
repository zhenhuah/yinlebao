 <!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Metronic | Form Stuff - Form Layouts</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH?>metronic/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo CSS_PATH?>metronic/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="<?php echo CSS_PATH?>metronic/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>select_xiala.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<!-- BEGIN CONTAINER -->

<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div>
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable-line boxless tabbable-reversed">
						<div class="tab-content">
							<div class="tab-pane active" id="tab_0">
								<div class="">
									<div class="portlet-body form">
									<h><strong>编辑商品：</strong></h>
										<!-- BEGIN FORM-->
										<form name="myform" action="?m=go3c&c=tvuser&a=goods_editdo" method="post" id="myform" class="form-horizontal">
											<div class="form-body">
												<div class="form-group">
													<label class="col-md-3 control-label">商品类别 </label>
													<div class="col-md-4">
															<p class="dys"><select name="oks" id="oks" onchange="document.getElementById('okw').value=this.value;">
															 <option value="玩具">玩具</option>
															 <option value="故事书">故事书</option>
															</select></p>
															<p class="dyw"><input type="text" name="type" id="okw" value="<?php echo $goodsdata['type']?>" /></p>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-3 control-label">商品名称</label>
													<div class="col-md-4">
														<input type="text" name="name" id="name"  class="form-control input-circle" value="<? echo $goodsdata['name']?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">商品编号</label>
													<div class="col-md-4">
														<input type="text" name="sign" id=""  class="form-control input-circle" value="<?php echo $goodsdata['sign']?>">
													</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">是否推荐</label>
													<div class="col-md-4">
														<span ><input type="radio" name="recommand" value="1">是</span>
														<span style='margin-left: 80px'><input type="radio" name="recommand" value="0">否</span>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-3 control-label">列表页图片</label>
													<div class="col-md-4">
														<div class="input-icon">	
															<input type="hidden" name="imgType" id="ImgText" value="ImgText">
															<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
															<input type="text" value="<?php echo $goodsdata['poster_list']?>" name="info[imageurl]" id="imageurl" class="form-control input-circle">
														</div>
													</div>
												</div>
												<div style="text-align: center; margin-left: -186px;margin-top: -14px;">
													<h>图片建议430x600px 大小800KB以内</h>
												</div>
												<div class="form-group">
													<div class="col-md-4" style="float: right; margin-right: 137px; margin-top: -51px;">
															<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
															<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="form-control input-circle" onchange="checkUpload(this);" style="border:0px;">
															<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-3 control-label">详情页图片</label>
													<div class="col-md-4">
														<div class="input-icon">	
															<input type="hidden" name="imgType" id="ImgText" value="ImgText">
															<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
															<input type="text" value="<?php echo $goodsdata['bigpic_list']?>" name="info[imageurl]" id="imageurl" class="form-control input-circle" style="height:200px">
														</div>
													</div>
												</div>
												<div style="text-align: center; margin-left: -186px;margin-top: -14px;">
													<h>图片建议尺寸430x600px 大小800KB以内</h>
												</div>
												<div class="form-group">
													<div class="col-md-4" style="float: right; margin-right: 137px; margin-top: -51px;">
															<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
															<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="form-control input-circle" onchange="checkUpload(this);" style="border:0px;">
															<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">详情页视频</label>
													<div class="col-md-4">
														<div class="input-icon">	
															<input type="hidden" name="imgType" id="ImgText" value="ImgText">
															<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
															<input type="text" value="<?php echo $goodsdata['video']?>" name="info[imageurl]" id="imageurl" class="form-control input-circle" style="height:200px">
														</div>
													</div>
												</div>
												<div style="text-align: center; margin-left: -186px;margin-top: -14px;">
													<h>视频建议尺寸1000x500px 大小10M以内,格式avi、png</h>
												</div>
												<div class="form-group">
													<div class="col-md-4" style="float: right; margin-right: 137px; margin-top: -51px;">
															<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
															<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="form-control input-circle" onchange="checkUpload(this);" style="border:0px;">
															<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">VIP会员价</label>
													<div class="col-md-4" style="width:12%">
														<input type="text" name="vip_price" id=""  class="form-control input-circle" value="<?php echo $goodsdata['vip_price'] ?>">
														<span class="money"style="float: right;margin-top: -25px;position: relative; left: 28px;">元</span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">会员价</label>
													<div class="col-md-4" style="width:12%">
														<input type="text" name="member_price" id=""  class="form-control input-circle" value="<?php echo $goodsdata['member_price']?>">
														<span class="money"style="float: right;margin-top: -25px;position: relative; left: 28px;">元</span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">基础价</label>
													<div class="col-md-4" style="width:12%">
														<input type="text" name="default_price" id=""  class="form-control input-circle" value="<?php echo $goodsdata['default_price'] ?>">
														<span class="money"style="float: right;margin-top: -25px;position: relative; left: 28px;">元</span>
													</div>
													
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<input type="hidden" name="id" value="<?php echo $goodsdata['id']?>" />
													<div class="col-md-offset-3 col-md-9">
														 <button name="dosubmit" id="dosubmit" class="btn btn-circle blue" type="submit"><?php echo L('submit')?></button>
														 <a class="cancel" onclick="history.go(-1)">取消</a>
													</div>
												</div>
											</div>
										</form>
										<!-- END FORM-->
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!--[if lt IE 9]>
<script src="<?php echo CSS_PATH?>metronic/respond.min.js"></script>
<script src="<?php echo CSS_PATH?>metronic/excanvas.min.js"></script> 
<![endif]-->

<script src="<?php echo CSS_PATH?>metronic/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo CSS_PATH?>metronic/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo CSS_PATH?>metronic/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo CSS_PATH?>metronic/metronic.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/layout.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/quick-sidebar.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/demo.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/form-samples.js"></script>
<script src="<?php echo JS_PATH?>formvalidator.js"></script>
<script src="<?php echo JS_PATH?>formvalidatorregex.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init(); // init quick sidebar
Demo.init(); // init demo features
   FormSamples.init();
});
</script>
<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#realname").formValidator({onshow:"<?php echo L('').L('')?>",onfocus:"<?php echo L('realname')?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('realname').L('between_2_to_20')?>"})
	$("#email").formValidator({onshow:"<?php echo L('input').L('email')?>",onfocus:"<?php echo L('input').L('email')?>",oncorrect:"<?php echo L('email').L('format_right')?>"}).regexValidator({regexp:"email",datatype:"enum",onerror:"<?php echo L('email').L('format_incorrect')?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=admin&c=admin_manage&a=public_email_ajx",
		datatype : "html",
		async:'false',
		success : function(data){	
            if( data == "1" )
			{
                return true;
			}
            else
			{
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('email_already_exists')?>",
		onwait : "<?php echo L('connecting_please_wait')?>"
	}).defaultPassed();
  })
</script>
<script type="text/javascript" src="<?php echo JS_PATH?>ajaxfileupload.js"></script>
															<script type="text/javascript">
															function ajaxFileUpload()
															{
																//优化 start 
																var fileValue = jQuery('#fileToUpload').val();
																if (fileValue != '')
																{
																	//验证文件类型					
																	var filepath=fileValue; 								
																	var extStart=filepath.lastIndexOf('.'); 
																	var ext=filepath.substring(extStart,filepath.length).toUpperCase(); 
																	if((ext != '.JPG') && (ext != '.JPEG') && (ext != '.PNG'))
																	{ 
																		alert("图片文件限于jpg,jpeg,png格式"); 
																		return false; 
																	}
																}else{
																	alert('请选择图片!');
																	return false;
																}
																//优化 end


																$("#loading")
																.ajaxStart(function(){
																	$(this).show();
																})
																.ajaxComplete(function(){
																	$(this).hide();
																});

																$.ajaxFileUpload
																(
																	{
																		url:"?m=go3c&c=task&a=inforfileupload&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
																		secureuri:false,
																		fileElementId:'fileToUpload',
																		dataType: 'json',
																		success: function (data, status)
																		{
																			if(typeof(data.error) != 'undefined')
																			{
																				if(data.error != '')
																				{
																					alert(data.error);
																				}else
																				{
																					if (data.msg != '0')
																					{
																						var web_url = $('#webSiteUrl').val();
																						//var sub = 'uploadfile/infor/'; 
																						//浏览
																						var set_img_url = web_url+data.msg;
																						$('#viewImgUrl').attr('target','_blank');	//设置另一个页面打开
																						$('#viewImgUrl').attr('href',set_img_url);	//设置
																						$('#viewImgUrl').show();
																						
																						$('#imageurl').val(data.msg);
																						alert('上传成功!');
																					}else{
																						$('#viewImgUrl').hide();
																						alert('上传失败!');											
																					}
																				}
																			}
																		},
																		error: function (data, status, e)
																		{
																			alert(e);
																		}
																	}
																)
																
																return false;
															}
															//文件上传验证
															function checkUpload(fileObj)
															{
																if(fileObj.value != '') 
																{
																	//方式一
																	//jQuery('#buttonUpload').show();

																	//方法二
																	ajaxFileUpload();
																}
																return false;
															}
															</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>