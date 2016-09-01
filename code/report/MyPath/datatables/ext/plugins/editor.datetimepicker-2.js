/**
 * Date and time picker in Editor, Bootstrap style. This plug-in provides
 * integration between [Bootstrap DateTimePicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
 * control and Editor. Fields can use this control by
 * specifying `datetime` as the Editor field type.
 *
 * @name Bootstrap DateTimePicker (2)
 * @summary Date and time input selector styled with Bootstrap
 * @requires [Bootstrap DateTimePicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
 * @depjs //cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js
 * @depjs //cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/2.1.30/js/bootstrap-datetimepicker.min.js
 * @depcss //cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/2.1.30/css/bootstrap-datetimepicker.css
 *
 * @opt `e-type object` **`opts`**: DateTimePicker initialisation options
 *     object. Please refer to the Bootstrap DateTimePicker documentation for
 *     the full range of options available.
 * @opt `e-type object` **`attr`**: Attributes that are applied to the `-tag
 *     div` wrapper element used for the date picker. This can be used to set
 *     `data` attributes which the DateTimePicker allows for setting additional
 *     options such as the date format.
 *
 * @method **`inst`**: Get the DateTimePicker instance so you can call its API
 *     methods directly.
 *
 * @example
 *     
 * new $.fn.dataTable.Editor( {
 *   "ajax": "php/dates.php",
 *   "table": "#example",
 *   "fields": [ {
 *          "label": "First name:",
 *          "name": "first_name"
 *      }, {
 *          "label": "Last name:",
 *          "name": "last_name"
 *      }, {
 *          "label": "Updated date:",
 *          "name": "updated_date",
 *          "type": "datetime",
 *          "opts": {
 *              pickTime: false
 *          }
 *      }, {
 *          "label": "Registered date:",
 *          "name": "registered_date",
 *          "type": "datetime"
 *      }
 *   ]
 * } );
 */

$.fn.dataTable.Editor.fieldTypes.datetime = $.extend( true, {}, $.fn.dataTable.Editor.models.fieldType, {
	"create": function ( conf ) {
		var that = this;

		conf._input = $(
				'<div class="input-group date" id="'+conf.id+'">'+
					'<input type="text" class="form-control" />'+
					'<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>'+
					'</span>'+
				'</div>'
            )
			.attr( $.extend( {}, conf.attr ) )
			.datetimepicker( $.extend( {}, conf.opts ) );

		return conf._input[0];
	},

	"get": function ( conf ) {
		return conf._input.children('input').val();
	},

	"set": function ( conf, val ) {
		conf._input.data("DateTimePicker").setDate( val );
	},

	// Non-standard Editor methods - custom to this plug-in. Return the jquery
	// object for the datetimepicker instance so methods can be called directly
	inst: function ( conf ) {
		return conf._input.data("DateTimePicker");
	}
} );

