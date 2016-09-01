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
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
<link href="<?php echo CSS_PATH?>table_form.css" rel="stylesheet" type="text/css" />
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
										<!-- BEGIN FORM-->
										<h><strong>添加会员：</strong></h>
										<form name="myform" action="?m=member&c=member_model&a=mem_mange" method="post" id="myform" class="form-horizontal">
											<div class="form-body">
												<div class="form-group">
													<label class="col-md-3 control-label">手机号</label>
													<div class="col-md-4">
														<input style="width:80%"type="text" name="i" id=""  class="form-control input-circle" value="">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label" >mac地址</label>
													<div class="col-md-4">
														<input style="width:80%"type="text" name="i" id=""  class="form-control input-circle" value="">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">添加时间</label>
													<div class="col-md-4">
														<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "starttime",
																trigger    : "starttime",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">姓名</label>
													<div class="col-md-4">
														<input style="width:40%"type="text" name="" id=""  class="form-control input-circle" value="">
													</div>
												</div>
												
												
												<div class="form-group">
													<label class="col-md-3 control-label">地址</label>
													<div class="col-md-4">
														<textarea rows="2" cols="20"style="width: 100%;border-radius: 10px !important;border-color: rgba(0,0,0,0.1);"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label">添加套餐</label>
													<div class="col-md-4">
														<input type="checkbox">A套餐
														<input type="text" value="<?php echo $Ataocan;?>" name="Ataocan" id="Ataocan" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "Ataocan",
																trigger    : "Ataocan",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-4">
														<input type="checkbox">B套餐
														<input type="text" value="<?php echo $Btaocan;?>" name="Btaocan" id="Btaocan" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "Btaocan",
																trigger    : "Btaocan",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-4">
														<input type="checkbox">C套餐
														<input type="text" value="<?php echo $Ctaocan;?>" name="Ctaocan" id="Ctaocan" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "Ctaocan",
																trigger    : "Ctaocan",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-4">
														<input type="checkbox">D套餐
														<input type="text" value="<?php echo $Dtaocan;?>" name="Dtaocan" id="Dtaocan" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "Dtaocan",
																trigger    : "Dtaocan",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-4">
														<input type="checkbox">E套餐
														<input type="text" value="<?php echo $Etaocan;?>" name="Etaocan" id="Etaocan" size="10" class="date" style="height:28px;border-radius: 10px !important;">
															<script type="text/javascript">
																Calendar.setup({
																weekNumbers: true,
																inputField : "Etaocan",
																trigger    : "Etaocan",
																dateFormat: "%Y-%m-%d",
																showTime: false,
																minuteStep: 1,
																onSelect   : function() {this.hide();}
																});
															</script>
													</div>
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														 <button name="dosubmit" id="dosubmit" class="btn btn-circle blue" type="submit" onclick="return confirm('确定提交吗')"><?php echo L('submit')?></button>
														 <a class="cancel"onclick="history.go(-1)">取消</a> 
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

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>