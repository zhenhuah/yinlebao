
/*
 * Editor client script for DB table t_catlist
 * Created by http://editor.datatables.net/generator
 */

(function($){

$(document).ready(function() {
	var editor = new $.fn.dataTable.Editor( {
		"ajax": "http://51ktv.iego.cn:8060/go3co2o/backend/templates/php/table.t_catlist.php",
		"table": "#t_catlist",
		"fields": [
			{
				"label": "Parent Cat",
				"name": "f_cat_parent_id"
			},
			{
				"label": "CatID",
				"name": "f_cat_id"
			},
			{
				"label": "Label",
				"name": "f_cat_label"
			},
			{
				"label": "Cat name",
				"name": "f_cat_name"
			},
			{
				"label": "Used for",
				"name": "f_cat_for",
				"type": "select",
				"def": "user",
				"options": [
					"user",
					"devel"
				]
			},
			{
				"label": "Url",
				"name": "f_edit_link"
			}
		]
	} );
	
												  // Activate an inline edit on click of a table cell
    $('#t_catlist').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            buttons: { label: '&gt;', fn: function () { this.submit(); } }
        } );
    } );

	$('#t_catlist').DataTable( {
		"dom": "Tfrtip",
		"ajax": "http://51ktv.iego.cn:8060/go3co2o/backend/templates/php/table.t_catlist.php",
		"columns": [
			{
				"data": "f_cat_parent_id"
			},
			{
				"data": "f_cat_id"
			},
				{
				"data": "f_cat_label"
			},
			{
				"data": "f_cat_name"
			},
			{
				"data": "f_cat_for"
			},
				{
				"data": "f_edit_link"
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

