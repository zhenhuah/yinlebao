<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title> 导入任务管理 </title>

	
	  <link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/css/demo.css">
	  <link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/css/chosen.css">
	  <link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/css/plugins/editor.title.css">	
		
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/select2/4.0.0/select2.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/selectize/0.12.1/selectize.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/media/css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/media/css/dataTables.tableTools.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Editor-PHP-1.4.2/css/dataTables.editor.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3cadmin/statics/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/Plugins/integration/bootstrap/3/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/font-Awesome/4.1.0/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/Plugins/integration/font-Awesome/dataTables.fontAwesome.css">
<!--		
		<script type="text/javascript" language="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/jquery/1.11.2/js/jquery-1.11.1.js"></script>
-->
		
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/media/js/jquery-1.11.2.js"></script>
		<script type="text/javascript" language="javascript" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/jquery/1.11.2/js/jquery-ui.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/media/js/jquery.dataTables.js"></script>


		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/bootstrap/3.0.3/js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/Plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/datatables/Datatables-1.10.7/extensions/TableTools/js/dataTables.tableTools.js"></script>	
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js"></script>
				
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/microplugin/microplugin.js"></script>
	  <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/selectize/sifter.js-master/sifter.js"></script>
	
	  <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/selectize/0.12.1/selectize.js"></script>
		
	
		

	
	
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/dataTables.editor.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/moment.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/editor.datetimepicker.js"></script>
		 <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/libs/chosen.jquery.js"></script>
	  <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/editor.chosen.js"></script>
	  <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/editor.title.js"></script>
	  <script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/editor.selectize.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/jquery.dataTables.rowReordering.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/jquery.dataTables.rowGrouping.js"></script>

		
		<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/table.t_import_tasks.js"></script>
		
		
			<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/select2/4.0.0/placeholders.jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3co2o/backend/assets/global/ajax/libs/select2/4.0.0/select2.full.js"></script>
	<script type="text/javascript" charset="utf-8" src="http://60.190.243.87:8060/go3cadmin/phpcms/modules/datatable/js/plugins/editor.select2.js"></script>
		
	</head>
	<body>
		<div class="container">
			<h3>
			</h3>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="t_import_tasks" width="100%">
				<thead>
					<tr>
						<th>编号</th>
						<th>任务名</th>
						<th>数据源</th>
						<th>数据模板</th>
						<th>周期</th>
						<th>时间</th>
						<th>自动导入</th>
						<th>导入后</th>			
							<th> 状态 </th>					
					</tr>
				</thead>
			</table>

		</div>
	</body>
</html>
