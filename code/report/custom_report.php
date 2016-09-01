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
    <div class="container">
      <div class="page-header">
        <h1>自定义报表</h1>
      </div>
        <table id="example" class="display" cellspacing="0" width="100%">
        </table>
    </div>


    <!-- 模态框（Modal） -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" 
       aria-labelledby="myLargeModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg" style="width:90%">
          <div class="modal-content">
            <div class="modal-header alert alert-warning" style="margin-bottom:0px;">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                      &times;
                </button>
                <h3 class="modal-title" id="myModalLabel">
                    自定义报表编辑
                </h3>
            </div>
         
         <table class="table table-bordered" data-resizable-columns-id="demo-table" id="demo">
            <thead>
              <tr>
                <th data-resizable-column-id="#">#</th>
                <th data-resizable-column-id="first_name">First Name</th>
                <th data-resizable-column-id="last_name">Last Name</th>
                <th data-resizable-column-id="username" data-noresize>Username</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

            <div class="modal-body" style="padding:0px 30px;">
                <div class="row" style="padding:0px;">
                  <div class="col-md-8 alert alert-success" style="padding:0px;margin-bottom:0px;">
                    <div class="panel panel-success">
                    <div class="panel-heading">
                        <form class="form-inline">
                            <div class="btn-group"> 
                                <button type="button" class="btn btn-primary" id="body-btn-add">添加</button>
                                <button type="button" class="btn btn-danger" id="body-btn-del">删除</button>
                                 <button type="button" class="btn btn-warning" id="body-btn-all-save">全部保存</button>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body" style="padding:0px 15px;">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover" id="body_css_template">
                        </table>
                    </div>
                </div>
              </div>
              <div class="col-md-4 alert alert-info" style="padding:0px;margin-bottom:0px;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">预览区</h3>
                    </div>
                    <div class="panel-body">
                        <div id="body-content" style="margin:0px auto;width:226.4px;height:300px;border:1px solid #000;position:relative">
                            <table border="1" id="thc" style="width:100%;cursor:pointer;color:#000000;text-align:center;">
                              <thead>
                                <tr>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              </div>
                </div>
                <div class="row alert alert-danger sr-only" style="margin-bottom:0px;" id="body-font_edit_area">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">编辑区</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-inline">
                                
                                <div class="form-group" id="body_csscontent_warp">
                                    <label for="body_csscontent">内容:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="body_csscontent" placeholder="请输入内容" />
                                    </div>
                                </div>
                                <div class="form-group sr-only" id="body_data_source_warp">
                                    <label for="data_source">选择数据源:</label>
                                    <div class="input-group">
                                        <select id="body_data_source" class="form-control">
                                            <option value="orders">订单表</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group sr-only" id="body_data_field_warp">
                                    <label for="body_data_field">选择字段:</label>
                                    <div class="input-group">
                                        <select id="body_data_field" class="form-control">
                                            <option value="id">订单号</option>
                                            <option value="username">用户名</option>
                                            <option value="mobile">手机号</option>

                                            <option value="created">创建时间</option>
                                            <option value="serviceid">服务员id</option>
                                            <option value="tableid">桌台名称</option>
                                            <option value="person">人数</option>
                                            <option value="status">状态</option>

                                            <option value="menu.id">菜品ID</option>
                                            <option value="menu.name">菜品名称</option>
                                            <option value="menu.count">数量</option>
                                            <option value="menu.price">单价</option>
                                            <option value="menu.tableid">桌台名称</option>
                                            <option value="menu.status">状态</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="my_id">
                                <input type="hidden" id="parent_id" />
                                <input type="hidden" id="current_id" />  
                                <button type="button" class="btn btn-primary" id="body-data-save">保存</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div><!-- /.modal-content-->
        </div>
    </div><!-- /.modal -->


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
    <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/buttons/dataTables.buttons.min.js"></script> 
    <!--select-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/datatables/select/dataTables.select.min.js"></script>
    <!--editor-->
    <script type="text/javascript" src="/go3cadmin/report/MyPath/editor/dataTables.editor.min.js"></script>
    <script type="text/javascript">
      var editor; // use a global for the submit and return data rendering in the examples
      $(document).ready(function() {
          //editor
          editor = new $.fn.dataTable.Editor( {
              ajax: "/go3cadmin/report/report_data.php",
              table: "#example",
              fields:[ 
                  {
                     label: "报表名称:",
                      name: "name"
                  }, 
                  {
                    label:"宽度:",
                    name:"width"
                  }
              ]
          } );
          //datatable
          $('#example').DataTable( {
              dom: "Bfrtip",
              ajax: "/go3cadmin/report/report_data.php",
              // oLanguage: {
              //   "sSearch": "快速搜索:",
              //   "sLengthMenu": "每页显示 _MENU_ 条记录",
              //   "sZeroRecords": "抱歉， 没有找到",
              //   "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
              //   "sInfoEmpty": "没有数据",
              //   "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
              //   "oPaginate": {
              //     "sFirst": "首页",
              //     "sPrevious": "前一页",
              //     "sNext": "后一页",
              //     "sLast": "尾页"
              //   },
              //   "sZeroRecords": "没有检索到数据",
              // },
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
              columns:[
                  {
                    data:"id",
                    title:"id"
                  },
                  {
                     title: "报表名称",
                     data: "name",
                     "sWidth": "*"
                   },
                   {
                        title:"宽度",
                        data:"width"
                   },
                   {
                      data: null,
                      title:"操作", 
                      defaultContent: '<a id="btn-edit-department" class="btn btn-primary btn-sm" data-toggle="modal">报表编辑</a>', 
                      orderable: false,
                      "sWidth": "100px"
                   }
              ],
              select: true,
              buttons: [
                  { extend: "create", editor: editor },
                  { extend: "edit",   editor: editor },
                  { extend: "remove", editor: editor }
              ]
          } );
          //点击自定义报表
          $('#example tbody').on('click', '#btn-edit-department', function () {
              var data = $.trim($(this).parents('tr').find("td").eq(0).text());
			  var name = $.trim($(this).parents('tr').find("td").eq(1).text());
			  console.log(data);
              location.href ='/go3cadmin/report/custom_report_format.php?id='+data+'&name'+name;
              //$('#myModal').modal('show');
          });
          //表格可拖拽列宽
        // $("#demo").resizableColumns({
         //   store: store
         // });

      } );
    </script>
</body>
</html>