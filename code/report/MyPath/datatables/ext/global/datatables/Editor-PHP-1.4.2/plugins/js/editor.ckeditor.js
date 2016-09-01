/**
 * Use the [CKEditor](http://ckeditor.com) WYSIWYG input control in Editor
 * to allow easy creation of complex HTML content in Editor.
 *
 * @name CKEditor
 * @summary WYSIWYG editor
 * @requires [CKEditor](http://ckeditor.com)
 *
 * @example
 *     
 * new $.fn.dataTable.Editor( {
 *   "ajax": "php/customers.php",
 *   "table": "#example",
 *   "fields": [ {
 *       "label": "Info:",
 *       "name": "info",
 *       "type": "ckeditor"
 *     }, 
 *     // additional fields...
 *   ]
 * } );
 */

(function ($, Editor) {

Editor.fieldTypes.ckeditor = $.extend( true, {}, Editor.models.fieldType, {
    "create": function ( conf ) {
        var that = this;
        var id = Editor.safeId( conf.id );

        conf._input = $('<div><textarea id="'+id+'"></textarea></div>');

        // CKEditor needs to be initialised on each open call
        this.on( 'onOpen.ckEditInit-'+id, function () {
            if ( $('#'+id).length ) {
                conf._editor = CKEDITOR.replace( id );

                if ( conf._initSetVal ) {
                    conf._editor.setData( conf._initSetVal );
                    conf._initSetVal = null;
                }
                else {
                    conf._editor.setData( '' );
                }
            }
        } );

        // And destroyed on each close, so it can be re-initialised on reopen
        this.on( 'onClose.ckEditInit-'+id, function () {
            if ( $('#'+id).length ) {
                conf._editor.destroy();
                conf._editor = null;
            }
        } );

        return conf._input;
    },

    "get": function ( conf ) {
        if ( ! conf._editor ) {
            return conf._initSetVal;
        }

        return conf._editor.getData();
    },

    "set": function ( conf, val ) {
        // If not ready, then store the value to use when the onOpen event fires
        if ( ! conf._editor ) {
            conf._initSetVal = val;
            return;
        }
        conf._editor.setData( val );
    },

    "enable": function ( conf ) {}, // not supported in CKEditor

    "disable": function ( conf ) {} // not supported in CKEditor
} );

})(jQuery, jQuery.fn.dataTable.Editor);
