
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
									<h><strong>编辑课程：</strong></h>
										<!-- BEGIN FORM-->
										<form name="myform" action="?m=go3c&c=tvuser&a=lesson_editdo&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post" id="myform" class="form-horizontal">
											<div class="form-body">
												<div class="form-group">
													<label class="col-md-3 control-label">选择套餐 </label>
													<div class="col-md-4">
															<p class="dys">
															<select name="" id="" onchange="document.getElementById('plan').value=this.value;">
																<option value="">请选择</option>
																<?php if(is_array($plan_data) ){foreach($plan_data as $v){?> 
																	<option value="<?php echo $v['name'] ?>" <?php if($plan == $v['name']){ echo 'selected';}?> >
																		<?php echo $v['name'] ?>
																	</option> 	
																<?php }} ?>	
															</select>
															</p>
															<p class="dyw"><input type="text" name="name" id="plan" value="<?php echo $plandata['name'] ?>" /></p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">选择期数</label>
													<div class="col-md-4">
														<p class="dys">
														<select name="oks1" id="oks1" onchange="document.getElementById('qishu').value=this.value;">
															<option value=""></option>	
															<option value="1期">1期</option>
															<option value="2期">2期</option>
															<option value="3期">3期</option>
															<option value="4期">4期</option>
															<option value="5期">5期</option>
															<option value="6期">6期</option>
															<option value="7期">7期</option>
															<option value="8期">8期</option>
															<option value="9期">9期</option>
															<option value="10期">10期</option>
															<option value="11期">11期</option>
															<option value="12期">12期</option>
														</select>
														</p>
															<p class="dyw"><input type="text" name="episode" id="qishu" value="<?php echo $lessondata['episode']?>" /></p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">课程名称</label>
													<div class="col-md-4">
														<input type="text" name="title" id="title"  class="form-control input-circle" value="<?php echo $lessondata['title']?>">                                                                                       
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">海报地址</label>
													<div class="col-md-4">
														<div class="input-icon">
															<input type="hidden" name="imgType" id="ImgText" value="ImgText">
															<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
															<input type="text" value="<?php echo $lessondata['poster_list']?>" name="poster_list" id="imageurl" class="form-control input-circle">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-4" style="float: right; margin-right: 137px; margin-top: -51px;">
															<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
															<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="form-control input-circle" onchange="checkUpload(this);" style="border:0px;">
															<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
															<a href="" style="display:none;" id="viewImgUrl"><font color="red"><?php echo L('预览')?></font></a>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">蓝光链接</label>
													<div class="col-md-4">
														<input type="text" name="bluelight_d" id="linking"  class="form-control input-circle" value="<?php echo $lessondata['bluelight_d']?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">超清链接</label>
													<div class="col-md-4">
														<input type="text" name="super_d" id="link"  class="form-control input-circle" value="<?php echo $lessondata['super_d']?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">高清链接</label>
													<div class="col-md-4">
														<input type="text" name="high_d" id="link"  class="form-control input-circle" value="<?php echo $lessondata['high_d']?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">标清链接</label>
													<div class="col-md-4">
														<input type="text" name="standard_d" id="link"  class="form-control input-circle" value="<?php echo $lessondata['standard_d']?>">
													</div>
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<input type="hidden" name="id" value="<?php echo $lessondata['id']?>" />
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
	$("#classname").formValidator({onshow:"<?php echo L('input').L('课程名称')?>",onfocus:"<?php echo L('请输入').L('课程名称')?>"}).inputValidator({min:3,max:20,onerror:"<?php echo L('课程名称').L('3-20字符')?>"})
	
	$("#plan").formValidator({onshow:"<?php echo L('请输入或者选择').L('套餐')?>",onfocus:"<?php echo L('请输入或者选择').L('套餐名称')?>"}).inputValidator({min:1,max:20,onerror:"<?php echo L('套餐名称').L('3-20字符')?>"})

	$("#qishu").formValidator({onshow:"<?php echo L('请输入或者选择').L('期数')?>",onfocus:"<?php echo L('请输入或者选择').L('期数')?>"}).inputValidator({min:1,max:20,onerror:"<?php echo L('期数').L('3-20字符')?>"})

	$("#linking").formValidator({onshow:"<?php echo L('输入链接地址').L('格式类似http://x.x.x/')?>",onfocus:"<?php echo L('请输入正确链接地址').L('格式类似http://x.x.x/')?>"}).inputValidator({min:15,max:1000,onerror:"<?php echo L('链接地址').L('格式类似http://x.x.x/')?>"}).regexValidator({isvalid:false,regexp:"[a-zA-z]+://[^s]*",datatype:'string',onerror:"<?php echo L

('输入链接地址有误')?>",validatetype:"RegexValidator"})

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