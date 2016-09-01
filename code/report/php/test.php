<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>首页</title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!--bootstrap-->
    <link rel="stylesheet" type="text/css" href="/test/MyPath/bootstrap/css/bootstrap.min.css">
    <!--datatables-->
    <link rel="stylesheet" type="text/css" href="/test/MyPath/datatables/jquery.dataTables.min.css"/>
    <!--buttons-->
    <link rel="stylesheet" type="text/css" href="/test/MyPath/datatables/buttons/buttons.dataTables.min.css">
    <!--select-->
    <link rel="stylesheet" type="text/css" href="/test/MyPath/datatables/select/select.dataTables.min.css">
    <!--editor-->
    <link rel="stylesheet" type="text/css" href="/test/MyPath/editor/editor.dataTables.min.css">
</head>
<body>
  
    <div class="container">
      <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Name</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Name</th>
          </tr>
        </tfoot>
      </table>
    </div>

    <!--JQuery-->
    <script type="text/javascript" src="/test/MyPath/js/jquery-1.11.3.js"></script>
    <!--bootstrap-->
    <script src="/test/MyPath/bootstrap/js/bootstrap.min.js"></script>
    <!--datatables-->
    <script type="text/javascript" src="/test/MyPath/datatables/jquery.dataTables.min.js"></script>
    <!--buttons-->
    <script type="text/javascript" src="/test/MyPath/datatables/buttons/dataTables.buttons.min.js"></script>
    <!--select-->
    <script type="text/javascript" src="/test/MyPath/datatables/select/dataTables.select.min.js"></script>
    <!--editor-->
    <script type="text/javascript" src="/test/MyPath/editor/dataTables.editor.min.js"></script>
    <script type="text/javascript">
      var editor; // use a global for the submit and return data rendering in the examples
       
      $(document).ready(function() {
          editor = new $.fn.dataTable.Editor( {
              ajax: "data.php",
              table: "#example",
              fields: [ {
                      label: "First name:",
                      name: "user_name"
                  }, 
                  //{
                  //     label: "Last name:",
                  //     name: "last_name"
                  // }, {
                  //     label: "Position:",
                  //     name: "position"
                  // }, {
                  //     label: "Office:",
                  //     name: "office"
                  // }, {
                  //     label: "Extension:",
                  //     name: "extn"
                  // }, {
                  //     label: "Start date:",
                  //     name: "start_date",
                  //     type: "date"
                  // }, {
                  //     label: "Salary:",
                  //     name: "salary"
                  // }
              ]
          } );
       
          $('#example').DataTable( {
              dom: "Bfrtip",
              ajax: "data.php",
              columns: [
                  // { data: null, render: function ( data, type, row ) {
                  //     // Combine the first and last names into a single table field
                  //     return data.first_name+' '+data.last_name;
                  // } },
                  { data: "user_name" },
                  // { data: "office" },
                  // { data: "extn" },
                  // { data: "start_date" },
                  // { data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
              ],
              select: true,
              buttons: [
                  { extend: "create", editor: editor },
                  { extend: "edit",   editor: editor },
                  { extend: "remove", editor: editor }
              ]
          } );
      } );
    </script>
</body>
</html>