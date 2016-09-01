var TableAjax = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

	
	
	
    var handleRecords = function () {

        var grid = new Datatable();
		require.config({
    baseUrl: 'js',
    paths: {
        jquery: 'http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min',
        datatables: 'http://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.0/js/jquery.dataTables.min',
        datatablesTools:'http://cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.min',
        datatablesEditor: 'http://isocia2.com/cms/js/DataTables-1.9.4/extras/Editor-1.2.3/media/js/dataTables.editor'
    },
    shim: {       
        datatables: {deps: ['jquery'],exports: "datatables" },
        datatablesTools: {deps: ['datatables']},       
        datatablesEditor: {deps: ['datatables', 'datatablesTools']}
    }
});
require(["jquery"], function($) {
var editor = new $.fn.dataTable.Editor( {});
});
/*
		var editor = new $.fn.dataTable.Editor( {
		"ajax": "http://127.0.0.1/yinlebao/phpcms/datatable/php/table.t_import_log.php",
		"table": "#datatable_ajax",
		"i18n": {
            create: {
                button: "\u65b0\u5efa",
                title:  "\u65b0\u5efa\u4e00\u9879",
                submit: "\u65b0\u5efa"
            },
            edit: {
                button: "\u4fee\u6539",
                title:  "\u4fee\u6539\u4e00\u9879",
                submit: "\u4fdd\u5b58"
            },
            remove: {
                button: "\u5220\u9664",
                title:  "\u5220\u9664",
                submit: "\u5220\u9664",
                confirm: {
                    _: "\u786e\u8ba4\u5220\u9664?",
                    1: "\u786e\u8ba4\u5220\u9664?"
                }
            },
            error: {
                system: "\u672a\u77e5\u9519\u8bef"
            }
        },
		"fields": [
			{
				"label": "\u4efb\u52a1ID",
				"name": "f_task_id"
			},
			{
				"label": "\u5bfc\u5165\u65f6\u95f4",
				"name": "f_starttime",
				"type": "date",
				"dateFormat": "D, d M y"
			},
			{
				"label": "\u6301\u7eed\u65f6\u95f4",
				"name": "f_duration"
			},
			{
				"label": "\u64cd\u4f5c\u4eba",
				"name": "f_responser",
				"type": "select",
				"options": [
					"\u81ea\u52a8",
					"\u8fdc\u7a0b\u8c03\u7528"
				]
			},
			{
				"label": "\u6210\u529f\u6570",
				"name": "f_success"
			},
			{
				"label": "\u5931\u8d25\u6570",
				"name": "f_failed"
			}
		]
	} );
		*/
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid) {
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                //"dom": "T<'clear'>lfrtip",
				"dom": "Tfrtip",
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": [
                    [10, 20, 50, 100, 150, -1],
                    [10, 20, 50, 100, 150, "All"] // change per page values here
                ],
                "pageLength": 10, // default record count per page
                "ajax": {
                    //"url": "demo/table_ajax.php", // ajax source
					"url": "http://127.0.0.1/yinlebao/phpcms/datatable/php/table.t_import_log.php", // ajax source
                },
				"columns": [
					{ "data": "DT_RowId" },
					{ "data": "f_task_id" },
					{ "data": "f_starttime" },
					{ "data": "f_duration" },
					{ "data": "f_responser" },
					{ "data": "f_success" },
					{ "data": "f_failed" }
				],
                "order": [
                    [1, "asc"]
                ]// set first column as a default sort by asc
            },
			"tableTools": {
			"sSwfPath": "/release-datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
			"sRowSelect": "os",
			"aButtons": [
				{ "sExtends": "editor_create", "editor": editor },
				{ "sExtends": "editor_edit",   "editor": editor },
				{ "sExtends": "editor_remove", "editor": editor }
			]
		}
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            } else if (action.val() == "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            initPickers();
            handleRecords();
        }

    };

}();