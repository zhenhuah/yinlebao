
/*
 * Editor client script for DB table plugins
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields": [
			{
				"label": "\u6587\u672c",
				"name": "plugins.text",
				"def":""
			},
			{
				"label": "\u5bc6\u7801",
				"name": "plugins.password",
				"type": "password",
				"def":""
			},
			{
				"label": "\u6587\u672c\u6846",
				"name": "plugins.textarea",
				"type": "textarea",
				"def":""
			},
			{
				"label": "\u5217\u8868\u9009\u62e9",
				"name": "plugins.selector",
				"type": "select",
				"def":"",
				"options": [
					"1",
					"2",
					"3"
				]
			},
			{
				"label": "checkbox",
				"name": "plugins.checkbox",
				"type": "checkbox",
				"separator": "|",
				"def":"",
				"options": [
					""
				]
			},
			{
				"label": "radio",
				"name": "plugins.radio",
				"type": "radio",
				"def":"",
				"options": [
					""
				]
			},
			{
				"label": "\u65f6\u95f4\u63a7\u4ef6",
				"name": "plugins.date",
				"type": "date",
				"def":"",
				"dateFormat": "D, d M y"
			},
			{
				"label": "\u53ea\u8bfb",
				"name": "plugins.readonly",
				"type": "readonly",
				"def": "\u53ea\u8bfb\u6587\u672c"
			},
			{
				"label": "upload:",
				"name": "plugins.image",
				"type": "upload",
				"display": function ( val, row ) {
					return val && row.image.webPath ?
						'<img src="'+row.image.webPath+'"/>' :
						'No image';
				}
       }
		]
	} );
	var tinymce = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[
		{
				"label": "Notes:",
				"name": "plugins.text",
				"type": "tinymce",
				"def": "",
				"opts": {
					"theme": "modern",
					"skin" : 'lightgray',
					"plugins" : [
								"advlist autolink lists link image charmap print preview hr anchor pagebreak",
								"searchreplace wordcount visualblocks visualchars code fullscreen",
								 "insertdatetime media nonbreaking save table contextmenu directionality",
								"emoticons template paste textcolor"//colorpicker textpattern 不能用？
							],
					"toolbar1": "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
					"toolbar2": "print preview media | forecolor backcolor emoticons",
					"image_advtab": true,
					"media_alt_source": false,
					"templates": [
							{"title": 'Test template 1', "content": 'Test 1'},
							{"title": 'Test template 2', "content": 'Test 2'}
						],
					"textpattern_patterns": [
							{"start": '*', "end": '*', "format": 'italic'},
							{"start": '**', "end": '**', "format": 'bold'},
							{"start": '#', "format": 'h1'},
							{"start": '##', "format": 'h2'},
							{"start": '###', "format": 'h3'},
							{"start": '####', "format": 'h4'},
							{"start": '#####', "format": 'h5'},
							{"start": '######', "format": 'h6'},
							{"start": '1. ', "cmd": 'InsertOrderedList'},
							{"start": '* ', "cmd": 'InsertUnorderedList'},
							{"start": '- ', "cmd": 'InsertUnorderedList'}
				]
				}
			}]
	});
	var select2 = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[
			{
				"label": "single select:",
				"name": "plugins.text",
				"type": "select2",
				"def":"",
				"ipOpts": [
					{ "label": "1 (highest)", "value": "1" },
					{ "label": "2",           "value": "2" },
					{ "label": "3",           "value": "3" },
					{ "label": "4",           "value": "4" },
					{ "label": "5 (lowest)",  "value": "5" }
				]
			},
			{
				"label": "multiple select:",
				"name": "plugins.password",
				"type": "select2",
				"def":"",
				"ipOpts": [
					{ "label": "1 (highest)", "value": "1" },
					{ "label": "2",           "value": "2" },
					{ "label": "3",           "value": "3" },
					{ "label": "4",           "value": "4" },
					{ "label": "5 (lowest)",  "value": "5" }
				],
				"opts": {
					"allowClear": true,
					"dir": "rtl",
					"theme": "classic",
					"tags": true,
					"multiple": true
				}
			},
			{
				"label": "chosen single:",
				"name": "plugins.textarea",
				"type": "chosen",
				"def":"",
				"options": [
					{ "label": "1 (highest)", "value": "1" },
					{ "label": "2",           "value": "2" },
					{ "label": "3",           "value": "3" },
					{ "label": "4",           "value": "4" },
					{ "label": "5 (lowest)",  "value": "5" }
				],
				"opts": {
					"disable_search": true,
					"inherit_select_classes": true,
				}
			},
			{
				"label": "chosen multiple:",
				"name": "plugins.selector",
				"type": "chosen",
				"def":"",
				"options": [
					{ "label": "1 (highest)", "value": "1" },
					{ "label": "2",           "value": "2" },
					{ "label": "3",           "value": "3" },
					{ "label": "4",           "value": "4" },
					{ "label": "5 (lowest)",  "value": "5" }
				],
				"opts": {
					"inherit_select_classes": true,
					"max_selected_options": Infinity,
					"multiple": true,
					"placeholder_text_multiple":"Select Some Options"
					
				}
			}
		] 
	} );
	var datepicker = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[ 
				{
					"label": "datepicker:",
					"name": "plugins.text",
					"type": "date",
					"def":"",
					"opts":{
						"showOnFocus": true,
						"format":"yyyy-mm-dd",
						"autoclose":false
					},
					"input":"plugins.text"
				},
				{
					"label": "datetime:",
					"name": "plugins.password",
					"type": "datetime",
					"def":"",
					"opts": {
						"showOnFocus": true,
						"pickTime": true
					}
				}
		] 
	} );
	
	var title = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[ 
				{
					"label": "Field set title",
					"name": "plugins.text",
					"type": "title"
				}
		] 
	} );
	
	var Masked = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[ 
				{
					"label": "Masked inputs:",
					"name": "plugins.text",
					"type": "mask",
					"mask": "0000/00/00",
					"placeholder": "YYYY/MM/DD"
				}
		] 
	} );
	
	var AutoComplete = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[ 
				{
					"label": "UI AutoComplete:",
					"name": "plugins.text",
					"type": "autoComplete",
					"opts": {
						"source": [
							"1",
							"2",
							"3adadf",
							"4",
							"5adadf"
						]
					}
				}
		] 
	} );
	
	var CKEditor = new $.fn.dataTable.Editor( {
		"ajax": "php/table.plugins.php",
		"table": "#plugins",
		"fields":[ 
				{
					"label": "CKEditor:",
					"name": "plugins.text",
					"type": "ckeditor"
				}
		] 
	} );
	
	$('#plugins').DataTable( {
		"dom": "Tfrtip",
		"ajax": "php/table.plugins.php",
		"columns": [
			{
				"data": "plugins.text"
			},
			{
				"data": "plugins.password"
			},
			{
				"data": "plugins.textarea"
			},
			{
				"data": "plugins.selector"
			},
			{
				"data": "plugins.checkbox"
			},
			{
				"data": "plugins.radio"
			},
			{
				"data": "plugins.date"
			},
			{
				"data": "plugins.readonly"
			},
			{
				"data": "plugins.notes"
			}
		],
		"tableTools": {
			"sRowSelect": "os",
			"aButtons": [
				{ "sExtends": "editor_create", "editor": editor ,"sButtonText": "general"},
				{ "sExtends": "editor_create", "editor": tinymce ,"sButtonText": "tinymce"},
				{ "sExtends": "editor_create", "editor": select2 ,"sButtonText": "select2"},
				{ "sExtends": "editor_create", "editor": datepicker ,"sButtonText": "datepicker"},
				{ "sExtends": "editor_create", "editor": CKEditor ,"sButtonText": "CKEditor"},
				{ "sExtends": "editor_create", "editor": title ,"sButtonText": "Field set title"},
				{ "sExtends": "editor_create", "editor": Masked ,"sButtonText": "Masked inputs"},
				{ "sExtends": "editor_create", "editor": AutoComplete ,"sButtonText": "UI AutoComplete"},
				{ "sExtends": "editor_remove", "editor": editor }
			]
		}
	} );
} );

}(jQuery));

