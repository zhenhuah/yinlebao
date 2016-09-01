
/*
 * Editor client script for DB table t_import_log
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		"ajax": "http://127.0.0.1/yinlebao/phpcms/modules/datatable/php/table.t_import_log.php",
		"table": "#t_import_log",
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

	$('#t_import_log').DataTable( {
			"dom": "T<'clear'>lfrtip",
		"ajax": "http://127.0.0.1/yinlebao/phpcms/modules/datatable/php/table.t_import_log.php",
		"language": {
				"url": 'http://127.0.0.1/yinlebao/phpcms/modules/datatable/js/i18n/Chinese.json'
		},
		"columns": [
			{
				"data": "f_task_id"
			},
			{
				"data": "f_starttime"
			},
			{
				"data": "f_duration"
			},
			{
				"data": "f_responser"
			},
			{
				"data": "f_success"
			},
			{
				"data": "f_failed"
			}
		],
		"tableTools": {
			"sRowSelect": "os",
			"aButtons": [
				{ "sExtends": "editor_create", "editor": editor },
				{ "sExtends": "editor_edit",   "editor": editor },
				{ "sExtends": "editor_remove", "editor": editor }
			]
		}
	} );
} );

}(jQuery));

