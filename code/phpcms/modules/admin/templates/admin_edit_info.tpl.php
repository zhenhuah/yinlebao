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
										<form name="myform" action="?m=admin&c=admin_manage&a=public_edit_info" method="post" id="myform" class="form-horizontal">
										<input type="hidden" name="info[userid]" value="<?php echo $userid?>"></input>
										<input type="hidden" name="info[username]" value="<?php echo $username?>"></input>
											<div class="form-body">
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('username')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<?php echo $username?></span>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('realname')?></label>
													<div class="col-md-4">
														<input type="text" name="info[realname]" id="realname"  class="form-control input-circle" value="<?php echo $realname?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('email')?></label>
													<div class="col-md-4">
														<div class="input-group">
															<span class="input-group-addon input-circle-left">
															<i class="fa fa-envelope"></i>
															</span>
															<input type="email" name="info[email]" id="email" class="form-control input-circle-right" value="<?php echo $email?>">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('uicon')?></label>
													<div class="col-md-4">
														<div class="input-icon">
															<input type="hidden" name="imgType" id="ImgText" value="ImgText">
															<input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
															<input type="text" value="<?php echo $imageurl?>" name="info[imageurl]" id="imageurl" class="form-control input-circle">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label"><?php echo L('upicon')?></label>
													<div class="col-md-4">
															<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
															<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="form-control input-circle" onchange="checkUpload(this);">
															<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
															</br>
															<a href="" style="display:none;" id="viewImgUrl"><font color="red"><?php echo L('Preview')?></font></a>
													</div>
												</div>
												<div class="form-group last">
													<label class="col-md-3 control-label"><?php echo L('Language')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<select name="info[lang]" >
        <?php if(in_array('af',$dir_array)) { ?><option value="af" <?php if($lang=='af') echo 'selected="selected"';?>>Afrikaans</option><?php }?>
        <?php if(in_array('sq',$dir_array)) { ?><option value="sq" <?php if($lang=='sq') echo 'selected="selected"';?>>Shqip - Albanian</option><?php }?>
        <?php if(in_array('ar',$dir_array)) { ?><option value="ar" <?php if($lang=='ar') echo 'selected="selected"';?>>العربية - Arabic</option><?php }?>
        <?php if(in_array('az',$dir_array)) { ?><option value="az" <?php if($lang=='az') echo 'selected="selected"';?>>Azərbaycanca - Azerbaijani</option><?php }?>
        <?php if(in_array('bn',$dir_array)) { ?><option value="bn" <?php if($lang=='bn') echo 'selected="selected"';?>>Bangla</option><?php }?>
        <?php if(in_array('eu',$dir_array)) { ?><option value="eu" <?php if($lang=='eu') echo 'selected="selected"';?>>Euskara - Basque</option><?php }?>
        <?php if(in_array('becyr',$dir_array)) { ?><option value="becyr" <?php if($lang=='becyr') echo 'selected="selected"';?>>Беларуская - Belarusian</option><?php }?>
        <?php if(in_array('belat',$dir_array)) { ?><option value="belat" <?php if($lang=='belat') echo 'selected="selected"';?>>Biełaruskaja - Belarusian latin</option><?php }?>
        <?php if(in_array('bs',$dir_array)) { ?><option value="bs" <?php if($lang=='bs') echo 'selected="selected"';?>>Bosanski - Bosnian</option><?php }?>
        <?php if(in_array('ptbr',$dir_array)) { ?><option value="ptbr" <?php if($lang=='ptbr') echo 'selected="selected"';?>>Portugu&ecirc;s - Brazilian portuguese</option><?php }?>
        <?php if(in_array('bg',$dir_array)) { ?><option value="bg" <?php if($lang=='bg') echo 'selected="selected"';?>>Български - Bulgarian</option><?php }?>
        <?php if(in_array('ca',$dir_array)) { ?><option value="ca" <?php if($lang=='ca') echo 'selected="selected"';?>>Catal&agrave; - Catalan</option><?php }?>
        <?php if(in_array('zh-cn',$dir_array)) { ?><option value="zh-cn" <?php if($lang=='zh-cn') echo 'selected="selected"';?>>中文 - Chinese simplified</option><?php }?>
        <?php if(in_array('zhtw',$dir_array)) { ?><option value="zhtw" <?php if($lang=='zhtw') echo 'selected="selected"';?>>中文 - Chinese traditional</option><?php }?>
        <?php if(in_array('hr',$dir_array)) { ?><option value="hr" <?php if($lang=='hr') echo 'selected="selected"';?>>Hrvatski - Croatian</option><?php }?>
        <?php if(in_array('cs',$dir_array)) { ?><option value="cs" <?php if($lang=='cs') echo 'selected="selected"';?>>Česky - Czech</option><?php }?>
        <?php if(in_array('da',$dir_array)) { ?><option value="da" <?php if($lang=='da') echo 'selected="selected"';?>>Dansk - Danish</option><?php }?>
        <?php if(in_array('nl',$dir_array)) { ?><option value="nl" <?php if($lang=='nl') echo 'selected="selected"';?>>Nederlands - Dutch</option><?php }?>
        <?php if(in_array('en',$dir_array)) { ?><option value="en" <?php if($lang=='en') echo 'selected="selected"';?>>English</option><?php }?>
        <?php if(in_array('et',$dir_array)) { ?><option value="et" <?php if($lang=='et') echo 'selected="selected"';?>>Eesti - Estonian</option><?php }?>
        <?php if(in_array('fi',$dir_array)) { ?><option value="fi" <?php if($lang=='fi') echo 'selected="selected"';?>>Suomi - Finnish</option><?php }?>
        <?php if(in_array('fr',$dir_array)) { ?><option value="fr" <?php if($lang=='fr') echo 'selected="selected"';?>>Fran&ccedil;ais - French</option><?php }?>
        <?php if(in_array('gl',$dir_array)) { ?><option value="gl" <?php if($lang=='gl') echo 'selected="selected"';?>>Galego - Galician</option><?php }?>
        <?php if(in_array('ka',$dir_array)) { ?><option value="ka" <?php if($lang=='ka') echo 'selected="selected"';?>>ქართული - Georgian</option><?php }?>
        <?php if(in_array('de',$dir_array)) { ?><option value="de" <?php if($lang=='de') echo 'selected="selected"';?>>Deutsch - German</option><?php }?>
        <?php if(in_array('el',$dir_array)) { ?><option value="el" <?php if($lang=='el') echo 'selected="selected"';?>>&Epsilon;&lambda;&lambda;&eta;&nu;&iota;&kappa;ά - Greek</option><?php }?>
        <?php if(in_array('he',$dir_array)) { ?><option value="he" <?php if($lang=='he') echo 'selected="selected"';?>>עברית - Hebrew</option><?php }?>
        <?php if(in_array('hi',$dir_array)) { ?><option value="hi" <?php if($lang=='hi') echo 'selected="selected"';?>>हिन्दी - Hindi</option><?php }?>
        <?php if(in_array('hu',$dir_array)) { ?><option value="hu" <?php if($lang=='hu') echo 'selected="selected"';?>>Magyar - Hungarian</option><?php }?>
        <?php if(in_array('id',$dir_array)) { ?><option value="id" <?php if($lang=='id') echo 'selected="selected"';?>>Bahasa Indonesia - Indonesian</option><?php }?>
        <?php if(in_array('it',$dir_array)) { ?><option value="it" <?php if($lang=='it') echo 'selected="selected"';?>>Italiano - Italian</option><?php }?>
        <?php if(in_array('ja',$dir_array)) { ?><option value="ja" <?php if($lang=='ja') echo 'selected="selected"';?>>日本語 - Japanese</option><?php }?>
        <?php if(in_array('ko',$dir_array)) { ?><option value="ko" <?php if($lang=='ko') echo 'selected="selected"';?>>한국어 - Korean</option><?php }?>
        <?php if(in_array('lv',$dir_array)) { ?><option value="lv" <?php if($lang=='lv') echo 'selected="selected"';?>>Latvie&scaron;u - Latvian</option><?php }?>
        <?php if(in_array('lt',$dir_array)) { ?><option value="lt" <?php if($lang=='lt') echo 'selected="selected"';?>>Lietuvių - Lithuanian</option><?php }?>
        <?php if(in_array('mkcyr',$dir_array)) { ?><option value="mkcyr" <?php if($lang=='mkcyr') echo 'selected="selected"';?>>Macedonian - Macedonian</option><?php }?>
        <?php if(in_array('ms',$dir_array)) { ?><option value="ms" <?php if($lang=='ms') echo 'selected="selected"';?>>Bahasa Melayu - Malay</option><?php }?>
        <?php if(in_array('mn',$dir_array)) { ?><option value="mn" <?php if($lang=='mn') echo 'selected="selected"';?>>Монгол - Mongolian</option><?php }?>
        <?php if(in_array('no',$dir_array)) { ?><option value="no" <?php if($lang=='no') echo 'selected="selected"';?>>Norsk - Norwegian</option><?php }?>
        <?php if(in_array('fa',$dir_array)) { ?><option value="fa" <?php if($lang=='fa') echo 'selected="selected"';?>>فارسی - Persian</option><?php }?>
        <?php if(in_array('pl',$dir_array)) { ?><option value="pl" <?php if($lang=='pl') echo 'selected="selected"';?>>Polski - Polish</option><?php }?>
        <?php if(in_array('pt',$dir_array)) { ?><option value="pt" <?php if($lang=='pt') echo 'selected="selected"';?>>Portugu&ecirc;s - Portuguese</option><?php }?>
        <?php if(in_array('ro',$dir_array)) { ?><option value="ro" <?php if($lang=='ro') echo 'selected="selected"';?>>Rom&acirc;nă - Romanian</option><?php }?>
        <?php if(in_array('ru',$dir_array)) { ?><option value="ru" <?php if($lang=='ru') echo 'selected="selected"';?>>Русский - Russian</option><?php }?>
        <?php if(in_array('srcyr',$dir_array)) { ?><option value="srcyr" <?php if($lang=='srcyr') echo 'selected="selected"';?>>Српски - Serbian</option><?php }?>
        <?php if(in_array('srlat',$dir_array)) { ?><option value="srlat" <?php if($lang=='srlat') echo 'selected="selected"';?>>Srpski - Serbian latin</option><?php }?>
        <?php if(in_array('si',$dir_array)) { ?><option value="si" <?php if($lang=='si') echo 'selected="selected"';?>>සිංහල - Sinhala</option><?php }?>
        <?php if(in_array('af',$dir_array)) { ?><option value="sk" <?php if($lang=='sk') echo 'selected="selected"';?>>Slovenčina - Slovak</option><?php }?>
        <?php if(in_array('sl',$dir_array)) { ?><option value="sl" <?php if($lang=='sl') echo 'selected="selected"';?>>Sloven&scaron;čina - Slovenian</option><?php }?>
        <?php if(in_array('es',$dir_array)) { ?><option value="es" <?php if($lang=='es') echo 'selected="selected"';?>>Espa&ntilde;ol - Spanish</option><?php }?>
        <?php if(in_array('sv',$dir_array)) { ?><option value="sv" <?php if($lang=='sv') echo 'selected="selected"';?>>Svenska - Swedish</option><?php }?>
        <?php if(in_array('tt',$dir_array)) { ?><option value="tt" <?php if($lang=='tt') echo 'selected="selected"';?>>Tatar&ccedil;a - Tatarish</option><?php }?>
        <?php if(in_array('th',$dir_array)) { ?><option value="th" <?php if($lang=='th') echo 'selected="selected"';?>>ภาษาไทย - Thai</option><?php }?>
        <?php if(in_array('tr',$dir_array)) { ?><option value="tr" <?php if($lang=='tr') echo 'selected="selected"';?>>T&uuml;rk&ccedil;e - Turkish</option><?php }?>
        <?php if(in_array('uk',$dir_array)) { ?><option value="uk" <?php if($lang=='uk') echo 'selected="selected"';?>>Українська - Ukrainian</option><?php }?>
        <?php if(in_array('uzcyr',$dir_array)) { ?><option value="uzcyr" <?php if($lang=='uzcyr') echo 'selected="selected"';?>>Ўзбекча - Uzbek-cyrillic</option><?php }?>
        <?php if(in_array('uzlat',$dir_array)) { ?><option value="uzlat" <?php if($lang=='uzlat') echo 'selected="selected"';?>>O&lsquo;zbekcha - Uzbek-latin</option><?php }?>

    </select>
														</span>
													</div>
												</div>
												<div class="form-group last">
													<label class="col-md-3 control-label"><?php echo L('lastlogintime')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<?php echo $lastlogintime ? date('Y-m-d H:i:s',$lastlogintime) : ''?></span>
													</div>
												</div>
												<div class="form-group last">
													<label class="col-md-3 control-label"><?php echo L('lastloginip')?></label>
													<div class="col-md-4">
														<span class="form-control-static">
														<?php echo $lastloginip?></span>
													</div>
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														 <button name="dosubmit" id="dosubmit" class="btn btn-circle blue" type="submit" ><?php echo L('submit')?></button>
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
	$("#realname").formValidator({onshow:"<?php echo L('input').L('realname')?>",onfocus:"<?php echo L('realname').L('between_2_to_20')?>"}).inputValidator({min:2,max:20,onerror:"<?php echo L('realname').L('between_2_to_20')?>"})
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