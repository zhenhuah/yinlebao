<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo L('admin_site_title')?></title>
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
								<div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gift"></i><?php echo L('MyProfile')?>
										</div>
										<div class="tools">
											<a href="javascript:;" class="collapse">
											</a>
											
											<a href="javascript:;" class="remove">
											</a>
										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form name="myform" action="?m=admin&c=admin_manage&a=public_edit_pwd" method="post" id="myform" class="form-horizontal">
										<input type="hidden" name="info[userid]" value="<?php echo $userid?>"></input>
										<input type="hidden" name="info[username]" value="<?php echo $username?>"></input>
											<div class="form-body">
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('username')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<?php echo $username?> (<?php echo L('realname')?> <?php echo $realname?>)</span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('email')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<?php echo $email?></span>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('old_password')?></label>
													<div class="col-md-4">
														<input type="password" name="old_password" id="old_password"  class="form-control input-circle">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('new_password')?></label>
													<div class="col-md-4">
														<input type="password" name="new_password" id="new_password"  class="form-control input-circle">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('new_pwdconfirm')?></label>
													<div class="col-md-4">
														<input type="password" name="new_pwdconfirm" id="new_pwdconfirm"  class="form-control input-circle">
													</div>
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														 <button name="dosubmit" id="dosubmit" class="btn btn-circle blue" type="submit"><?php echo L('submit')?></button>
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
	$("#old_password").formValidator({empty:true,onshow:"<?php echo L('not_change_the_password_please_leave_a_blank')?>",onfocus:"<?php echo L('password').L('between_6_to_20')?>",oncorrect:"<?php echo L('old_password_right')?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20')?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=admin&c=admin_manage&a=public_password_ajx",
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
		onerror : "<?php echo L('old_password_wrong')?>",
		onwait : "<?php echo L('connecting_please_wait')?>"
	});
	$("#new_password").formValidator({empty:true,onshow:"<?php echo L('not_change_the_password_please_leave_a_blank')?>",onfocus:"<?php echo L('password').L('between_6_to_20')?>"}).inputValidator({min:6,max:20,onerror:"<?php echo L('password').L('between_6_to_20')?>"});
	$("#new_pwdconfirm").formValidator({empty:true,onshow:"<?php echo L('not_change_the_password_please_leave_a_blank')?>",onfocus:"<?php echo L('input').L('passwords_not_match')?>",oncorrect:"<?php echo L('passwords_match')?>"}).compareValidator({desid:"new_password",operateor:"=",onerror:"<?php echo L('input').L('passwords_not_match')?>"});
  })
</script>
</body>
<!-- END BODY -->
</html>