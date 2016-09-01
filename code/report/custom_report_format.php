<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>自定义报表</title>
    <style type="text/css">
      th { white-space: nowrap; }
      .red-table-td{
            /*background-color: #ff0000;*/
            background-color: #f5f5f5;
        }

    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--bootstrap-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/bootstrap/css/bootstrap.min.css">
    <!--datatables-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/jquery.dataTables.min.css"/>
    <!--buttons-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/buttons/buttons.dataTables.min.css">
    <!--select-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/datatables/select/select.dataTables.min.css">
    <!--editor-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/editor/editor.dataTables.min.css">
    <!--表格可拖拽-->
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/css/jquery.resizableColumns.css">
    <link rel="stylesheet" type="text/css" href="/go3cadmin/report/MyPath/css/demo.css">
</head>
<body>
    <div style="padding:0px 30px;">
        <div class="row">
            <div class="col-md-12 alert alert-info" style="padding:0px;margin-bottom:0px;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">预览区</h3>
                    </div>
                    <div class="panel-body">
                        <div id="body-content" style="margin:0px auto;width:100%;height:100px;position:relative">
                            <table border="1" id="thc" style="margin:0px auto;width:1024px;height:100px;cursor:pointer;color:#000000;text-align:center;">
                              <thead>
                                <tr style="height:50px;">
                                </tr>
                              </thead>
                              <tbody>
                                <tr style="height:50px;">
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="padding:0px;">
          <div class="col-md-6 alert alert-success" style="padding:0px;margin-bottom:0px;">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <form class="form-inline">
                        <div class="btn-group"> 
                            <button type="button" class="btn btn-primary" id="body-btn-add">添加</button>
                            <button type="button" class="btn btn-danger" id="body-btn-del">删除</button>
                            <button type="button" class="btn btn-warning" id="body-btn-all-save">全部保存</button>
                            <button type="button" class="btn btn-warning" id="body-show">显示</button>
                        </div>
                    </form>
                </div>
                <div class="panel-body" style="padding:0px 15px;">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover" id="body_css_template">
                    </table>
                </div>
            </div>
         </div>

         <div class="col-md-6 alert alert-danger" style="margin-bottom:0px;" id="body-font_edit_area">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">编辑区</h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="fontName">字体:</label>
                            <div class="input-group">
                                <select id="fontName" class="form-control">
                                    <option value="Arial">Arial</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="width">宽度:</label>
                            <div class="input-group" >
                                <input type="number" class="form-control" id="width" width="请输入宽度,不填自动填充" min="100" max="400" value="100">
                                <div class="input-group-addon">px</div>
                              </div>
                        </div>

                        <div class="form-group">
                            <label for="align">对齐方式:</label>
                            <div class="input-group">
                                <select id="align" class="form-control">
                                    <option value="left">靠左</option>
                                    <option value="right">靠右</option>
                                    <option value="center">居中</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="color">颜色:</label>
                            <div class="input-group">
                                <input type="color" class="form-control" id="color" placeholder="请选择颜色">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="size">字体大小:</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="size" placeholder="请输入字体大小" min="12" max="40" value="12">
                                <div class="input-group-addon">px</div>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="weight">粗体:</label>
                            <div class="input-group">
                                <select id="weight" class="form-control">
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                    <option value="500">500</option>
                                    <option value="600">600</option>
                                    <option value="700">700</option>
                                    <option value="800">800</option>
                                    <option value="900">900</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="body_csscontent_warp">
                            <label for="body_csscontent">标题:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="body_csscontent" placeholder="请输入内容" />
                            </div>
                        </div>

                        <div class="form-group sr-only" id="body_data_source_warp">
                            <label for="data_source">选择数据源:</label>
                            <div class="input-group">
                                <select id="body_data_source" class="form-control">
                                </select>
                            </div>
                        </div>

                        <div class="form-group sr-only" id="body_data_field_warp">
                            <label for="body_data_field">选择字段:</label>
                            <div class="input-group">
                                <select id="body_data_field" class="form-control">
                                </select>
                            </div>

                            <label for="orderable">允许排序:</label>
                            <div class="input-group">
                                <select id="orderable" class="form-control">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                             <label for="status">是否检索:</label>
                            <div class="input-group">
                                <select id="status" class="form-control">
                                    <option value="on">是</option>
                                    <option value="off">否</option>
                                </select>
                            </div>
                            <label for="filter">过滤方式:</label>
                            <div class="input-group">
                                <select id="filter" class="form-control">
                                    <option value="select">选择框</option>
                                    <option value="range">范围框</option>
                                </select>
                            </div>

                            <label for="data_type">数据类型:</label>
                            <div class="input-group">
                                <select id="data_type" class="form-control">
                                    <option value="datatime">日期</option>
                                    <option value="number">数值</option>
                                </select>
                            </div>

                        </div>
                        <input type="hidden" value="<?php echo $_GET['id']?>" id="report_id">
                        <button type="button" class="btn btn-primary" id="body-data-save">保存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!--JQuery-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/js/jquery-1.11.3.js"></script>
    <!--表格可拖拽-->
     <script src="/go3cadmin/report/MyPath/js/store.js"></script>
     <!--<script src="/go3cadmin/report/MyPath/js/jquery.resizableColumns.js"></script>-->
    <!--bootstrap-->
    <script src="/go3cadmin/report/MyPath/bootstrap/js/bootstrap.min.js"></script>
    <!--datatables-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/jquery.dataTables.min.js"></script> 
    <!--buttons-->
<!--     // <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/buttons/dataTables.buttons.min.js"></script>  -->
    <!--select-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/select/dataTables.select.min.js"></script>
    <!--editor-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/editor/dataTables.editor.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var dataSet;
            $.ajax({
                type: 'POST',
                async: false,
                url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_data',
                data: {
                    func:'get_data',
                    report_id:<?php echo $_GET['id'];?>,
                },
                success: function(msg){
                    var message=$.parseJSON(msg);
                    if ($.trim(message['status'])=="success") {
                        dataSet = message['message'];
                    } 
                    else{
                    }
                }
            });
            //有数据才回填
            if(dataSet){
                $.each(dataSet, function(k1,v1){
                  if(v1.position == "thead"){
                    var col = $('<td></td>');
                    col.text(v1.title);
                    col.attr('father', v1.position);
                    col.attr('fontname', v1.font);
                    col.attr('align',v1.align);
                    col.attr('font_color', v1.font_color);
                    col.attr('font_size', v1.font_size);
                    col.attr('font_weight', v1.font_weight);
                    col.attr('title', v1.title);
                    col.attr('width', v1.width);
                    //样式
                    col.css('font-family', v1.font);
                    col.css('color', v1.font_color);
                    col.css('font-size', v1.font_size + 'px');
                    col.css('font-weight', v1.font_weight);
                    col.css('text-align', v1.align);
                    col.css('width', v1.width+'px');
                    //console.log(col);
                    $("#thc > thead > tr").append(col); 
                  }
                  else if(v1.position == "tbody"){
                    var col = $('<td></td>');
                    col.text(v1.title);
                    col.attr('father', v1.position);
                    col.attr('fontname', v1.font);
                    col.attr('align',v1.align);
                    col.attr('font_color', v1.font_color);
                    col.attr('font_size', v1.font_size);
                    col.attr('font_weight', v1.font_weight);
                    col.attr('title', v1.title);
                    col.attr('dataField',v1.dataField);
                    col.attr('dataSource',v1.dataSource);
                    col.attr('orderable',v1.orderable);
                    col.attr('width', v1.width);
                    col.attr('filter', v1.filter);
					col.attr('status', v1.status);
                    col.attr('data_type', v1.data_type);
                    //样式
                    col.css('font-family', v1.font);
                    col.css('color', v1.font_color);
                    col.css('font-size', v1.font_size + 'px');
                    col.css('font-weight', v1.font_weight);
                    col.css('text-align', v1.align);
                    col.css('width', v1.width + 'px');

                    $("#thc > tbody > tr").append(col);
                  }
                });
            }
			
            //表头事件绑定
            $("#thc thead tr td").unbind('click').on('click',function(){
              if($(this).attr("content")!=''){
                $("#body_csscontent").val($(this).attr("content"));
              }
              else{
                $("#body_csscontent").val("");
              }
              $("#thc tr td").removeClass('red-table-td');
              $(this).addClass('red-table-td');
                //数据回填
                //字体
                $('#fontName').val($(this).attr("fontname"));
                //width
                $('#width').val($(this).attr("width"));
                //位置
                //$('#align').val($(this).attr("align"));
                $('#align option[value="'+ $(this).attr("align") +'"]').attr("selected",true);
                //font_color
                $('#color').val($(this).attr("font_color"));
                //font_size
                $('#size').val($(this).attr("font_size"));
                //font_weight
                //$('#weight').val($(this).attr("font_weight"));
                $('#weight option[value="'+ $(this).attr("font_weight") +'"]').attr("selected",true);
                //内容回填
                $('#body_csscontent').val($(this).text());


              $('#body-font_edit_area').removeClass('sr-only');
              $('#body_csscontent_warp').removeClass('sr-only');

              $('#body_data_source_warp').addClass('sr-only');
              $('#body_data_field_warp').addClass('sr-only');
            });

            //表体事件绑定
            $("#thc tbody tr td").unbind('click').on('click', function(){
              if($(this).attr("dataSource")!=''){
                var dataSource = $(this).attr("dataSource");
                var dataField = $(this).attr("dataField");
                $('#body_data_source option[value="'+ dataSource +'"]').attr("selected",true);
                $('#body_data_field option[value="'+ dataField +'"]').attr("selected",true);
              }
                //数据回填
                //字体
                $('#fontName').val($(this).attr("fontname"));
                //width
                $('#width').val($(this).attr("width"));
                //位置
                //$('#align').val($(this).attr("align"));
                $('#align option[value="'+ $(this).attr("align") +'"]').attr("selected",true);
                //font_color
                $('#color').val($(this).attr("font_color"));
                //font_size
                $('#size').val($(this).attr("font_size"));
                //font_weight
                //$('#weight').val($(this).attr("font_weight"));
                $('#weight option[value="'+ $(this).attr("font_weight") +'"]').attr("selected",true);
                //orderable 是否允许排序
                $('#orderable option[value="'+ $(this).attr("orderable") +'"]').attr("selected",true);
                //过滤
                $('#filter option[value="'+ $(this).attr("filter") +'"]').attr("selected",true);
				//检索
                $('#status option[value="'+ $(this).attr("status") +'"]').attr("selected",true);
                //数据类型
                $('#data_type option[value="'+ $(this).attr("data_type") +'"]').attr("selected",true);

                $("#thc tr td").removeClass('red-table-td');
                $(this).addClass('red-table-td');
                $('#body-font_edit_area').removeClass('sr-only');
                $('#body_csscontent_warp').addClass('sr-only');
                $('#body_data_source_warp').removeClass('sr-only');
                $('#body_data_field_warp').removeClass('sr-only');


            });

            //datatable
            $('#body_css_template').DataTable( {
                "searching" : false,//不显示搜索框
                "bLengthChange": false,//不显示选择显示几条
                "bDestroy":true,
                "info": false,
                "iDisplayLength": 5,//显示几行
                  // ajax: "report_data.php",
                  data: dataSet,
                  oLanguage: {
                    "sSearch": "快速搜索:",
                    "sLengthMenu": "每页显示 _MENU_ 条记录",
                    "sZeroRecords": "抱歉， 没有找到",
                    "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
                    "sInfoEmpty": "没有数据",
                    "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
                    "oPaginate": {
                      "sFirst": "首页",
                      "sPrevious": "前一页",
                      "sNext": "后一页",
                      "sLast": "尾页"
                    },
                    "sZeroRecords": "没有检索到数据",
                  },
                  columns:[
                      {
                        data:"id",
                        title:"id"
                      },
                      {
                         title: "位置",
                         data: "position",
                         "sWidth": "*"
                       },
                       {
                            title:"字体",
                            data:"font"
                       },
					   {
                            title:"标题",
                            data:"title"
                       }
                       //,
                       // {
                       //    data: null,
                       //    title:"操作", 
                       //    defaultContent: '<a id="btn-edit-department" class="btn btn-primary btn-sm" data-toggle="modal">报表编辑</a>', 
                       //    orderable: false,
                       //    "sWidth": "100px"
                       // }
                  ],
                  select: true,
            } );

            //全部保存
            $('#body-btn-all-save').click(function(){
                var data1 = new Array();
                //表头的
                $("#thc thead tr td").each(function(k,v){
                    var position = "thead";
                    var report_id = $('#report_id').val();
                    var col = k;
                    var font = $(this).attr("fontname");
                    var align = $(this).attr("align");
                    var width = $(this).attr("width");
                    var font_color = $(this).attr("font_color");
                    var font_size = $(this).attr("font_size");
                    var font_weight = $(this).attr("font_weight");
                    var title = $(this).attr("title");
                    data1.push({position:position,report_id:report_id,col:col,font:font,align:align,font_color:font_color,font_size:font_size,font_weight:font_weight,title:title,width:width});

                });
                var report_id = $('#report_id').val();
                if(data1.length > 0){
                    $.post("/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_datasavethead",{
                            func:'insert_thead',
                            data:data1,
                            report_id:report_id,
                        },
                        function(msg){
                            var message=$.parseJSON(msg);
                            if ($.trim(message['status'])=="success") {
                              alert(message['message']);
                            } 
                            else {
                              alert(message['message']);
                            }
                        }
                    );
                }

                var data2 = new Array();
                //表体的
                $("#thc tbody tr td").each(function(k,v){
                    var position = "tbody";
                    var col = k;
                    var font = $(this).attr("fontname");
                    var align = $(this).attr("align");
                    var width = $(this).attr("width");
                    var font_color = $(this).attr("font_color");
                    var font_size = $(this).attr("font_size");
                    var font_weight = $(this).attr("font_weight");
                    var orderable = $(this).attr("orderable");
                    var dataSource = $(this).attr("dataSource");
                    var dataField = $(this).attr("dataField");
                    var title = $(this).attr("title");
                    var data_type = $(this).attr("data_type");
                    var filter = $(this).attr("filter");
					var status = $(this).attr("status");
                    data2.push({data_type:data_type,filter:filter,title:title,position:position,report_id:report_id,col:col,font:font,align:align,font_color:font_color,font_size:font_size,font_weight:font_weight,orderable:orderable,dataSource:dataSource,dataField:dataField,width:width,status:status});
                });
                var report_id = $('#report_id').val();
                if(data2.length > 0){
                    $.post("/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_datasavetbody",{
                            func:'insert_tbody',
                            data:data2,
                            report_id:report_id
                        },
                        function(msg){
                            var message=$.parseJSON(msg);
                            if ($.trim(message['status'])=="success") {
                              alert(message['message']);
                            } 
                            else {
                              alert(message['message']);
                            }
                        }
                    );
                }
            });


            //添加行和列事件
            $('#body-btn-add').click(function(){
              //var rows = document.getElementById("example_table").rows.length; 
              //表格列数 
              $col = $("<td my-data=" +  document.getElementById("thc").rows.item(0).cells.length + ">表头"+document.getElementById("thc").rows.item(0).cells.length +"</td>");
              $col.attr('index',document.getElementById("thc").rows.item(0).cells.length);
              $col.attr('father','thead');

              $col1 = $("<td my-data=" + document.getElementById("thc").rows.item(0).cells.length + ">表体"+ document.getElementById("thc").rows.item(0).cells.length+"</td>");
              $col1.attr('index',document.getElementById("thc").rows.item(0).cells.length);
              $col1.attr('father','tbody');

              $("#thc > thead > tr").append($col);  
              $("#thc > tbody > tr").append($col1); 

                //表头事件绑定
                $("#thc thead tr td").unbind('click').on('click',function(){
                  if($(this).attr("content")!=''){
                    $("#body_csscontent").val($(this).attr("content"));
                  }
                  else{
                    $("#body_csscontent").val("");
                  }
                  $("#thc tr td").removeClass('red-table-td');
                  $(this).addClass('red-table-td');
                  $('#body-font_edit_area').removeClass('sr-only');
                  $('#body_csscontent_warp').removeClass('sr-only');

                  $('#body_data_source_warp').addClass('sr-only');
                  $('#body_data_field_warp').addClass('sr-only');
                });

                //表体事件绑定
                $("#thc tbody tr td").unbind('click').on('click', function(){
                  if($(this).attr("dataSource")!=''){
                    var dataSource = $(this).attr("dataSource");
                    var dataField = $(this).attr("dataField");
                    $('#body_data_source option[value="'+ dataSource +'"]').attr("selected",true);
                    $('#body_data_field option[value="'+ dataField +'"]').attr("selected",true);
                  }
                  $("#thc tr td").removeClass('red-table-td');
                  $(this).addClass('red-table-td');
                  $('#body-font_edit_area').removeClass('sr-only');
                  $('#body_csscontent_warp').addClass('sr-only');
                  $('#body_data_source_warp').removeClass('sr-only');
                  $('#body_data_field_warp').removeClass('sr-only');
                });
                
            });

            //body-show
            $('#body-show').click(function(){
                location.href = "/go3cadmin/report/report.php?id=<?php echo $_GET['id']?>&name=<?php echo $_GET['name']?>";
            });

            //单项保存事件
            $('#body-data-save').click(function(){
                $('#thc tr td').each(function(e){
                  if($(this).hasClass('red-table-td')){
                    if($(this).attr("father") == "thead"){
                      //字体 font
                      var fontName = $.trim($('#fontName').val());
                      $(this).css('font-style',fontName);
                      $(this).attr("fontname",fontName);
                      //对其方式
                      var align = $.trim($('#align').val());
                      $(this).css('text-align',align);
                      $(this).attr("align",align);
                      //font_color
                      var color = $.trim($('#color').val());
                      $(this).css('color',color);
                      $(this).attr("font_color",color);
                      //font_size
                      var size = $.trim($('#size').val());
                      $(this).css('font-size',size+'px');
                      $(this).attr("font_size",size);
                      //font_weight
                       var weight = $.trim($('#weight').val());
                      $(this).css('font-weight',weight);
                      $(this).attr("font_weight",weight);
                      //title
                      var title = $.trim($("#body_csscontent").val());
                      $(this).attr("title", title);
                      $(this).text(title);
                      //宽度
                       var width = $.trim($("#width").val());
                      $(this).attr("width", width);
                      $(this).css('width', width+'px');
                    }
                    else{
                      //字体 font
                      var fontName = $.trim($('#fontName').val());
                      $(this).css('font-style',fontName);
                      $(this).attr("fontname",fontName);
                      //对其方式
                      var align = $.trim($('#align').val());
                      $(this).css('text-align',align);
                      $(this).attr("align",align);
                      //font_color
                      var color = $.trim($('#color').val());
                      $(this).css('color',color);
                      $(this).attr("font_color",color);
                      //font_size
                      var size = $.trim($('#size').val());
                      $(this).css('font-size',size+'px');
                      $(this).attr("font_size",size);
                      //font_weight
                       var weight = $.trim($('#weight').val());
                      $(this).css('font-weight',weight);
                      $(this).attr("font_weight",weight);
                      //orderable 是否允许排序
                      var orderable = $.trim($('#orderable').val());
                      $(this).attr("orderable", orderable);
                      //数据源
                      $(this).attr("dataSource",$.trim($("#body_data_source").val()));
                      $(this).attr("dataField",$.trim($("#body_data_field").val()));
                      //字段
                      $(this).attr("content",$.trim($("#body_data_field").find("option:selected").text()));
                      $(this).text($("#body_data_field").find("option:selected").text());
                      //宽度
                      var width = $.trim($("#width").val());
                      $(this).attr("width", width);
                      $(this).css('width', width+'px');
                      //字段别名
                      $(this).attr("title", $("#body_data_field").find("option:selected").text());
                      //过滤形式
                      $(this).attr("filter", $('#filter').val());
					   //检索
                      $(this).attr("status", $('#status').val());
                      //数据类型
                      $(this).attr("data_type",$('#data_type').val());
                    }
                  } 
                });
            });

            //表格可拖拽列宽
            //$("#demo").resizableColumns({
            //    store: store
            //});
			//获取数据来源(字典里的数据表格)
			var datafield;
            $.ajax({
                type: 'POST',
                async: false,
                url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_datafield',
                data: {
                    func:'custom_datafield',
                },
                success: function(msg){
                    var message=$.parseJSON(msg);
                    if ($.trim(message['status'])=="success") {
                        datafield = message['message'];
                    } 
                    else{
                    }
                }
            });
			if(datafield){
				//console.log(datafield);
                $.each(datafield, function(k1,v1){
					var item="<option value="+v1.tablen+">"+v1.alname+"</option>";
					$("#body_data_source").append(item);
				});
			}
			$('#body_data_source').change(function(){
				$('#body_data_field').find('option').remove();
				var body_data_source = $.trim($('#body_data_source').val());
				if (body_data_source!=''){
					$.ajax({ 
						type: 'POST',
						async: false,
						url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_datafieldlist',
						data: {
							func:'custom_datafieldlist',
							tablen:body_data_source,
						},
						success: function (msg) { 
							var message=$.parseJSON(msg);
							if ($.trim(message['status'])=="success") {
								datafield = message['message'];
								$.each(datafield, function(k1,v1){
									var item="<option value="+v1.name+">"+v1.name+"</option>";
									$("#body_data_field").append(item);
								});
							} 
						}
					});
				}
			});
			//删除选定的行
			$('#body-btn-del').click(function(){
				var id = $("#body_css_template tbody tr.selected").children("td:eq(0)").text();
				alert(id);
				if(id){
					$.ajax({
						type: 'POST',
						async: false,
						url: '/go3cadmin/index.php?m=go3c&c=tvuser&a=custom_datadel',
						data: {
							id:id,
						},
						success: function(msg){
							var message=$.parseJSON(msg);
							if ($.trim(message['status'])=="success") {
								alert(message['message']);
							} 
						}
					});
				}
			});
			
			
        });
    </script>
</body>
</html>