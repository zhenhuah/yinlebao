<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../../go3cadmin/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../../go3cadmin/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="../../go3cadmin/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="../../go3cadmin/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../go3cadmin/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="../../go3cadmin/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="http://www.go3c.tv:8060/go3co2o/backend/assets/global/datatables/Editor-PHP-1.4.2/css/dataTables.editor.css">
<link rel="stylesheet" type="text/css" href="http://www.go3c.tv:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/media/css/dataTables.tableTools.css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-body">
							<div class="table-container">
								<table class="table table-striped table-bordered table-hover" id="datatable_ajax">
								<thead>
								<tr role="row" class="heading">
									<th width="2%">
										<input type="checkbox" class="group-checkable">
									</th>
									<th width="5%">
										 任务ID
									</th>
									<th width="10%">
										 导入时间
									</th>
									<th width="10%">
										 持续时间
									</th>
									<th width="10%">
										 操作人
									</th>
									<th width="10%">
										 成功数
									</th>
									<th width="10%">
										 失败数
									</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../go3cadmin/assets/global/plugins/respond.min.js"></script>
<script src="../../go3cadmin/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="../../go3cadmin/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../../go3cadmin/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../../go3cadmin/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../../go3cadmin/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../go3cadmin/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../../go3cadmin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../go3cadmin/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../../go3cadmin/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>

<!--
<script type="text/javascript" charset="utf-8" src="http://www.go3c.tv:8060/go3co2o/backend/templates/js/libs/require.js"></script>	
-->
<script type="text/javascript" charset="utf-8" src="http://www.go3c.tv:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/extensions/TableTools/js/dataTables.tableTools.js"></script>	
<script type="text/javascript" charset="utf-8" src="http://www.go3c.tv:8060/go3co2o/backend/templates/js/dataTables.editor.js"></script>	
<script src="../../go3cadmin/assets/global/scripts/datatable.js"></script>
<script src="../../go3cadmin/assets/admin/pages/scripts/table-importlog.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
        jQuery(document).ready(function() {  	
           Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init(); // init quick sidebar
Demo.init(); // init demo features
           TableAjax.init();
        });
    </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>