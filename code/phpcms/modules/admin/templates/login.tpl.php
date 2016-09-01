<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title><?php echo L('phpcms_logon')?></title>
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
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo CSS_PATH?>metronic/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo CSS_PATH?>metronic/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/layout.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo CSS_PATH?>metronic/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo CSS_PATH?>metronic/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="index.html">
	<img src="<?php echo IMG_PATH?>logo-shop-red.png" alt=""/>
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="index.php?m=admin&c=index&a=login&dosubmit=1" method="post" name="myform" autocomplete="on" onSubmit="return logondo();">
		<h3 class="form-title">Sign In</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo L('username')?></label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" id="username"/>
			<span id="username_id" style="display:none"><font color="red">用户名不能为空!</font>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo L('password')?></label>
			<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="password"/>
			<span id="password_id" style="display:none"><font color="red">密码不能为空!</font>
		</div>
		<div class="form-actions">
			<label class="rememberme check">
			<input type="checkbox" name="remember" value="1" checked="checked"/>记住密码 </label>
			<button type="submit" class="btn btn-success uppercase">登陆</button>
			
		</div>
		<div class="create-account">
			<p>
				<a href="javascript:;" id="register-btn" class="uppercase"></a>
			</p>
		</div>
	</form>
	<!-- END LOGIN FORM -->
</div>
<div class="copyright" style="display: none">
	 2015 © Go3co2o Admin.
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo CSS_PATH?>metronic/jquery.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo CSS_PATH?>metronic/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo CSS_PATH?>metronic/metronic.js" type="text/javascript"></script>
<script src="<?php echo CSS_PATH?>metronic/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
//Metronic.init(); // init metronic core components
//Layout.init(); // init current layout
Login.init();
//Demo.init();
});
</script>
<script type="text/javascript" src="statics/js/base64.js"></script>
<script type="text/javascript">
function logondo(){
	var username = $.trim($('#username').val());
	if (username != '')
	{
		$('#username_id').hide();
	}else{
		$('#username_id').show();
		return false;
	}
	var password = $.trim($('#password').val());
	if (password != '')
	{
		$('#password_id').hide();
	}else{
		$('#password_id').show();
		return false;
	}
}
</script>
<!-- END JAVASCRIPTS -->
</body>
</html>