<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>报表显示</title>
    <style type="text/css">
      th { white-space: nowrap; }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--bootstrap-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/bootstrap/css/bootstrap.css">
    <!--datatables-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/jquery.dataTables.min.css"/>
    
    <link rel="stylesheet" type="text/css" media="all" href="/go3cadmin/report/MyPath/css/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" media="all" href="/go3cadmin/report/MyPath/css/daterangepicker-1.3.7.css" />
    <link href="/go3cadmin/report/MyPath/css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" media="all" href="daterangepicker.css" />-->

    <!--buttons-->
    <!-- <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/buttons/buttons.dataTables.min.css"> -->
    <!--select-->
    <!-- <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/select/select.dataTables.min.css"> -->
    <!--editor-->
    <!-- <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/editor/editor.dataTables.min.css"> -->
    <style type="text/css">
      .center{
        text-align:center;
      }
      .left{
        text-align:left;
      }
      .right{
        text-align:right;
      },
      td.details-control {
    background: url('MyPath/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('MyPath/images/details_close.png') no-repeat center center;
}
.explain-col {
    background: #fffced none repeat scroll 0 0;
    border: 1px solid #ffbe7a;
    line-height: 20px;
    padding: 8px 10px;
}
    </style>
</head>
<body>
    <div class="container">
      <div class="page-header">
        <h1><?php echo $_GET['name']?></h1>
      </div>
      <div id="lwj" class="explain-col">
          显示隐藏列: 
        </div>
      <div id="thc" class="explain-col">
        检索:
		 <div id="thc1">
		 </div>
      </div>


      <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">   
      </table>
    </div>

    <!--JQuery-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/js/jquery-1.11.3.js"></script>
    <!--bootstrap-->
    <script src="/go3cadmin/report/MyPath/bootstrap/js/bootstrap.min.js"></script>
    <!--datatables-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/go3cadmin/report/MyPath/js/moment.js"></script>
    <script type="text/javascript" src="/go3cadmin/report/MyPath/js/daterangepicker-1.3.7.js"></script>
    <!--buttons-->
   <!--<script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/buttons/dataTables.buttons.min.js"></script> -->
    <!--select-->
    <!-- <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/select/dataTables.select.min.js"></script> -->
    <!--editor-->
    <!-- <script type="text/javascript" src="/go3cadmin/report/MyPath/editor/dataTables.editor.min.js"></script> -->
    <script type="text/javascript">
	function format ( d ) {
		// `d` is the original data object for the row
		return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
			'<tr>'+
				'<td></td>'+
				'<td>菜1:</td>'+
				'<td>菜1</td>'+
			'</tr>'+
			'<tr>'+
				'<td></td>'+
				'<td>菜2:</td>'+
				'<td>菜2</td>'+
			'</tr>'+
			'<tr>'+
				'<td></td>'+
				'<td>菜3:</td>'+
				'<td>菜3</td>'+
			'</tr>'+
		'</table>';
	}
      $(document).ready(function() {
		  alert(1);
		window.dataSet;
            $.ajax({
                type: 'POST',
                async: false,
                url: '/go3cadmin/report/php/Database/custom_datasel.php?pc_hash=G1B1rW',
                data: {
                            id:<?php echo $_GET['id'];?>
                        },
                success: function(msg){
                        var message=$.parseJSON(msg);
                        if ($.trim(message['status'])=="success") {
                            window.dataSet = message['message'];
                          //alert(message['message']);
                        } 
                        else {
                          //alert(message['message']);
                        }
               }
            });
		$.ajax({
			type: 'POST',
			async: false,
			url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_dataselthead&pc_hash=G1B1rW',
			data: {
						report_id:<?php echo $_GET['id'];?>
					},
			success: function(msg){
					var message=$.parseJSON(msg);
					if ($.trim(message['status'])=="success") {
						thead = message['message'];
					} 
					else {
					}
		   }
        });
		console.log(thead);
		$.ajax({
			type: 'POST',
			async: false,
			url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_dataseltbody&pc_hash=G1B1rW',
			data: {
						report_id:<?php echo $_GET['id'];?>
					},
			success: function(msg){
					var message=$.parseJSON(msg);
					if ($.trim(message['status'])=="success") {
						tbody = message['message'];
					} 
					else {
					}
		   }
        });
		var column_data = new Array();
		$.each(tbody,function(k,v){
			console.log(v.status);
			if(v.status == 'on'){
				if(k == 0)
				  var a = $('<a class="toggle-vis" data-column="'+k+'">'+v.dataField+'</a>');
				else
				  var a = $('<a class="toggle-vis" data-column="'+k+'">-'+v.dataField+'</a>');
			}
            $("#lwj").append(a);
            var w = "*";
            if(v.width != '')
              w = v.width + 'px';
            var order = true;
            if(v.orderable == 0)
              order = false;

            column_data.push({title:v.dataField, data:v.dataField, orderable:order, sWidth:w});
          });
		  
		  column_data.push({"className":'details-control',"orderable":false,"data":null,"defaultContent": ''});
		  
			function checkTime(startTime, endTime){ 
				var sDate = new Date(startTime.replace(/-/g, "//"));
				var eDate = new Date(endTime.replace(/-/g, "//"));
				return sDate<eDate;
				// var start=new Date(startTime.replace("-", "/").replace("-", "/"));  
				// var end=new Date(endTime.replace("-", "/").replace("-", "/"));  
				// if(end<start){  
				//     return false;  
				// }  
				// return true;  
			}
			function checktyUndefined(value){
			  return (typeof(value) == "undefined");
			}
			$.fn.dataTable.ext.search.push(
				function( settings, data, dataIndex ) {
					 var min = window.start;
					 var max = window.end;
					 var date = data[1];
					if ( ( checktyUndefined( min ) && checktyUndefined( max ) ) ||
						 ( checktyUndefined( min ) && checkTime(date, max)) ||
						 ( checktyUndefined( max ) && checkTime(min, date)) ||
						 ( checkTime(min, date) && checkTime(date, max) ) )
					{
						return true;
					}
					return false;
				}
			);
			
			var config = {
              "dom":
                   "<'row'<'span9'l<'#mytoolbox'>><'span3'f>r>"+
                   "t"+
                   "<'row'<'span6'i><'span6'p>>"  ,
              data: dataSet,
              "language": {
                   "sProcessing": "处理中...",
                   "sLengthMenu": "显示 _MENU_ 项结果",
                   "sZeroRecords": "没有匹配结果",
                   "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                   "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                   "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                   "sInfoPostFix": "",
                   "sSearch": "搜索:",
                   "sUrl": "",
                   "sEmptyTable": "表中数据为空",
                   "sLoadingRecords": "载入中...",
                   "sInfoThousands": ",",
                   "oPaginate": {
                       "sFirst": "首页",
                       "sPrevious": "上页",
                       "sNext": "下页",
                       "sLast": "末页"
                   },
                   "oAria": {
                       "sSortAscending": ": 以升序排列此列",
                       "sSortDescending": ": 以降序排列此列"
                   }
               },
              columns:column_data,
              select: true,
              initComplete: function () {

                  this.api().columns().every( function (k) {
                      var column = this;
                      //获得需要select过滤的col
                      //console.log(this);
                      //alert(k);
                      if(k == 4)
                        return true;
                      //alert(k);
					  if(tbody[k].status == 'on'){  //过滤部分关键字进行检索					  
                      if(tbody[k].filter == "select"&&tbody[k].data_type == "number"){
                        $('#thc').append(tbody[k].title + ":");
                        var select = $('<select><option value=""></option></select>')
                            .appendTo( $('#thc'))//$(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
         
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
         
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                      }
                      else if(tbody[k].filter == "range" && tbody[k].data_type == "datatime"){


                         // alert(tbody[k].data_type);


		var dataPlugin ='<div id="reportrange" class="pull-left dateRange" style="width:400px"> '+
			   '日期：<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> '+
			   '<span id="searchDateRange"></span>  '+
			   '<b class="caret"></b></div> ';
		$('#thc1').append(dataPlugin);
		//时间插件
		$('#reportrange span').html(moment().subtract('hours', 1).format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss'));

		window.start = moment().subtract('hours', 1).format('YYYY-MM-DD HH:mm:ss');
		window.end = moment().format('YYYY-MM-DD HH:mm:ss');

	$('#reportrange').daterangepicker(
	{
	   // startDate: moment().startOf('day'),
	   //endDate: moment(),
	   //minDate: '01/01/2012',    //最小时间
	   maxDate : moment(), //最大时间
	   dateLimit : {
		   days : 30
	   }, //起止时间的最大间隔
	   showDropdowns : true,
	   showWeekNumbers : false, //是否显示第几周
	   timePicker : true, //是否显示小时和分钟
	   timePickerIncrement : 60, //时间的增量，单位为分钟
	   timePicker12Hour : false, //是否使用12小时制来显示时间
	   ranges : {
		   //'最近1小时': [moment().subtract('hours',1), moment()],
		   '今日': [moment().startOf('day'), moment()],
		   '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
		   '最近7日': [moment().subtract('days', 6), moment()],
		   '最近30日': [moment().subtract('days', 29), moment()]
	   },
	   opens : 'right', //日期选择框的弹出位置
	   buttonClasses : [ 'btn btn-default' ],
	   applyClass : 'btn-small btn-primary blue',
	   cancelClass : 'btn-small',
	   format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
	   separator : ' to ',
	   locale : {
		   applyLabel : '确定',
		   cancelLabel : '取消',
		   fromLabel : '起始时间',
		   toLabel : '结束时间',
		   customRangeLabel : '自定义',
		   daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
		   monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
			   '七月', '八月', '九月', '十月', '十一月', '十二月' ],
		   firstDay : 1
	   }
	}, 

		function(start, end, label) {//格式化日期显示框
		  //获得开始和结束的时间
		  window.start = start.format('YYYY-MM-DD HH:mm:ss');
		  window.end = end.format('YYYY-MM-DD HH:mm:ss');
		   $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
		}
	);
	//设置日期菜单被选项  --开始--
	var dateOption ;
	if("${riqi}"=='day') {
	   dateOption = "今日";
	}else if("${riqi}"=='yday') {
	   dateOption = "昨日";
	}else if("${riqi}"=='week'){
	   dateOption ="最近7日";
	}else if("${riqi}"=='month'){
	   dateOption ="最近30日";
	}else if("${riqi}"=='year'){
	   dateOption ="最近一年";
	}else{
	   dateOption = "自定义";
	}
	$(".daterangepicker").find("li").each(function (){
	   if($(this).hasClass("active")){
		   $(this).removeClass("active");
	   }
	   if(dateOption==$(this).html()){
		   $(this).addClass("active");
	   }
	});
	//设置日期菜单被选项  --结束--
	//选择时间后触发重新加载的方法
	$("#reportrange").on('apply.daterangepicker',function(){
		table.draw();
	});
	function getParam(url) {
	   var data = decodeURI(url).split("?")[1];
	   var param = {};
	   var strs = data.split("&");
	   for(var i = 0; i<strs.length; i++){
		   param[strs[i].split("=")[0]] = strs[i].split("=")[1];
	   }
	   return param;
	}

				  }
				  } //过滤部分关键字进行检索-end
			  } );

			  //表头添加样式
			  alert(thead);
			  $.each(thead,function(k,v){
				$('#example thead tr').each(function(){
				  $(this).find('th').eq(k).text(v.title);
				  $(this).find('th').eq(k).addClass(v.align);
				  $(this).find('th').eq(k).css('font-family',v.font);
				  $(this).find('th').eq(k).css('font-weight',v.font_weight);
				  $(this).find('th').eq(k).css('font-size',v.font_size + 'px');
				  $(this).find('th').eq(k).css('color',v.font_color);
				});
			  });
			  //表体添加样式
			  $.each(tbody,function(k,v){
				$('#example tbody tr').each(function(){
				  $(this).find('td').eq(k).addClass(v.align);
				  $(this).find('td').eq(k).css('font-family',v.font);
				  $(this).find('td').eq(k).css('font-weight',v.font_weight);
				  $(this).find('td').eq(k).css('font-size',v.font_size + 'px');
				  $(this).find('td').eq(k).css('color',v.font_color);
				});
			  });
		 },
	  };
	
		var table = $('#example').DataTable(config);
		
		 $('#example tbody').on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
	 
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}
			else {
				// Open this row
				row.child( format(row.data()) ).show();
				tr.addClass('shown');
			}
		} );

	  $('a.toggle-vis').on( 'click', function (e) {
		$("#example").DataTable({"sAjaxSource": "/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_dataselone&pc_hash=G1B1rW","bServerSide": true,"bDestroy": true});

		
		  e.preventDefault();
		  // Get the column API object
		  var column = table.column( $(this).attr('data-column') );
		  // Toggle the visibility
		  column.visible( ! column.visible() );
	  } );
			
    } );
    </script>
</body>
</html>