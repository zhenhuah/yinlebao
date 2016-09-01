
/*
 * Editor client script for DB table t_import_tasks
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		"ajax": "http://127.0.0.1/yinlebao/phpcms/modules/datatable/php/table.t_import_tasks.php",
		"table": "#t_import_tasks",
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
				"label": "\u4efb\u52a1\u7f16\u53f7",
				"name": "f_task_id"
			},
			{
				"label": "\u4efb\u52a1\u540d",
				"name": "f_task_name"
			},
			{
				"label": "\u6570\u636e\u6e90",
				"name": "f_data_id"
			},
			{
				"label": "\u6570\u636e\u6a21\u677f",
				"name": "f_template_id"
			},
			{
				"label": "\u5bfc\u5165\u5468\u671f",
				"name": "f_peroid",
				"type": "select",
				"ipOpts": [
					{ "label": "\u6bcf\u5468", "value": "1" },
					{ "label": "\u6bcf\u5929",           "value": "2" },
					{ "label": "\u6bcf\u5468\u4e94",           "value": "3" },
				],
				"opts": {
					"placeholder": "\u9009\u62e9\u0031\u5230\u591a\u79cd\u5bfc\u5165\u5468\u671f",
					"allowClear": true,
			//		"dir": "rtl",
					"theme": "classic",
					"tags": true,
					"multiple": true,
					"tokenSeparators": [',']
				}
			
			},
			{
				"label": "\u5bfc\u5165\u65f6\u95f4",
				"name": "f_time",
				"type": "datetime",
				 "opts": {
            format: 'P hh:ii',
            autoclose: true,
            todayBtn: true,
            startDate: "2015-07-04 10:00",
        		minuteStep: 5,
        		showMeridian: true,
        		pickerPosition: "bottom-left",
        		todayHighlight:true,
        		keyboardNavigation:true,
        		"language":"en",
        		pickerReferer:'input',
        		startView:'day',//0:hour,1:day,2:month,3:year, 4:decade
        		 
        		//linkField: "mirror_field",
        		//linkFormat: "yyyy-mm-dd hh:ii"
        }
			},
			{
				"label": "\u81ea\u52a8\u5bfc\u5165",
				"name": "f_auto",
				"type": "select",
				"options": [
					"\u662f",
					"\u5426"
				]
			},
			{
				"label": "\u5bfc\u5165\u524d",
				"name": "f_pre_scripts"
			},
			{
				"label": "\u5bfc\u5165\u811a\u672c",
				"name": "f_scripts"
			},
			{
				"label": "\u5bfc\u5165\u540e",
				"name": "f_post_scripts",
				"type":"select",
				"ipOpts":[
					"\u53d1\u6d88\u606f",
					"\u81ea\u52a8\u4e0a\u7ebf"
				],
				"opts": {
					"placeholder": "\u9009\u62e9\u4e00\u5230\u591a\u79cd\u5bfc\u5165\u6570\u636e\u540e\u5904\u7406\u65b9\u5f0f",
					"allowClear": true,
			//		"dir": "rtl",
					"theme": "classic",
					"tags": true,
					"multiple": true,
					"tokenSeparators": [',']
				}
				
			},
			{
				"label": "\u6392\u5e8f\u7528",
				"name": "f_mid",
				"def":"1",
				"type": "hidden"
			},
			{
				"label": "\u72b6\u6001",
				"name": "f_status",
				"def":"idle",
				"type": "select",
				"options":[
					"idle",
					"importing"
				]
			}
		]
	} );

	var table =$('#t_import_tasks').dataTable( {
		"dom": "T<'clear'>lfrtip",
		"ajax": "http://127.0.0.1/yinlebao/phpcms/modules/datatable/php/table.t_import_tasks.php",
			"language": {
				"url": 'http://127.0.0.1/yinlebao/phpcms/modules/datatable/js/i18n/Chinese.json'
		},
		"columns": [
			{
				"data": "f_task_id"
			},
			{
				"data": "f_task_name"
			},
			{
				"data": "f_data_id"
			},
			{
				"data": "f_template_id"
			},
			{
				"data": "f_peroid"
			},
			{
				"data": "f_time"
			},
			{
				"data": "f_auto"
			},
			{
				"data": "f_post_scripts"
			},
				{
				"data": "f_status"
			}				
		],
		"tableTools": {
			"sRowSelect": "os",
			"aButtons": [
				{ "sExtends": "editor_create", "editor": editor ,"title":"Create New Import "},
				{ "sExtends": "editor_edit",   "editor": editor },
				{ "sExtends": "editor_remove", "editor": editor }
			]
		}
	} ).rowReordering({
				sURL:"php/lib/Database/updateOrder.php",
				sRequestType: "GET",
				sTable:"t_import_tasks",
				iIndexColumn:0,
			// 'callback': function(){updateOrder();},
				fnAlert: function(message) {

					//oState.iCurrentPosition, oState.iNewPosition, oState.sDirection
					//alert("order"+table.sSelector);
					console.log(table.rowReordering.sSelector);
				}
			});
} );

}(jQuery));

