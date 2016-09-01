/**
 * The ability to upload files plays and important part in complex forms and
 * data structures. This plug-in adds that ability to Editor by presenting an
 * Ajax file upload control to the end user. In this way any file types can be
 * uploaded and assigned to a field's value.
 *
 * This plug-in is described to operate with the `Upload` class of the PHP and
 * .NET library for Editor. please refer to the documentation for each for
 * complete details on how to use them ([PHP](/manual/php/upload),
 * [.NET](/manual/net/upload)).
 *
 * The plug-in can be used by simply including the Javascript and CSS for it on
 * your page and setting the `e-init fields.type` option to be `-string upload`.
 *
 * There are a number of options available to customise the control displayed to
 * the end user (documented below), but the primary option you will wish to use
 * is the `render` option. This option defines a function that displays
 * information to the end user about the file that is currently selected for the
 * field (be it an existing value on edit, or a newly uploaded file). As each
 * database structure and application is unique, it is important that you define
 * this function as appropriate for your application.
 *
 * @name Upload
 * @summary Ajax file upload input control with support for drop and drag
 *   upload, clearing selected files and content display control.
 *
 * @scss editor.upload.scss
 * @image upload-1.png Upload with no image selected
 * @image upload-2.png Image uploaded
 * @image upload-3.png Table showing images
 * @image upload-4.png Upload without clear and drag options
 *
 * @opt `e-type string` **`clearText`** (default - empty string): Enable the
 *   _clear_ button and set the text to show in the button. When set users will
 *   have the ability to click the button which results in the field's value
 *   being cleared (i.e. set to be an empty string). When not set, the clear
 *   button is not shown and there is no ability to clear the field's value.
 * @opt `e-type boolean` **`dragDrop`** (default - `true`): Enable / disable the
 *   drag and drop area allowing uses to drag files from their operating system
 *   into Editor. File are automatically uploaded when dropped.
 * @opt `e-type string` **`dragDropText`** (default -
 *   `Drag and drop a file here to upload`: Text to show in the drag and drop
 *   area.
 * @opt `e-type function` **`render`** (no default): Function which is used to
 *   display information about the file that is currently selected by the
 *   field's value. This function should return HTML that is shown in the Editor
 *   form to describe the file to the end user. It can be a simple descriptive
 *   piece of text (a file name for example) or render HTML such as an `-tag img`
 *   tag. The function is given two parameters: 1) The value of the field,
 *   2) The full data structure for the whole row (which can be used if your
 *   file information comes from joined fields).
 * @opt `e-type string` **`uploadText`** (default - `Choose file...`): Text
 *   shown in the _upload file_ button. This is really a `<input type="file">`
 *   input control, but styled to look like a button.
 *
 * @example
 *
 * // In this example an image can be uploaded in the form. The image is shown
 * // in the DataTable through the `columns.render` method creating an `<img>`
 * // tag. The image is also shown in the Editor form using the `display` option
 * // (documented above).
 * new $.fn.dataTable.Editor( {
 *   ajax: "php/staff.php",
 *   table: "#staff",
 *   fields: [ {
 *       label: "Info:",
 *       name: "info",
 *       type: "upload"
 *       display: function ( val, row ) {
 *           return val && row.image.webPath ?
 *               '<img src="'+row.image.webPath+'"/>' :
 *               'No image';
 *       },
 *     }, 
 *     // additional fields...
 *   ]
 * } );
 * 
 * $('#example').DataTable( {
 *     dom: "Tfrtip",
 *     ajax: "php/staff.php",
 *     columns: [
 *         { data: "staff.name" },
 *         { data: "staff.title" },
 *         {
 *             data: "image",
 *             defaultContent: "No image",
 *             render: function ( d ) {
 *                 return d.webPath ? 
 *                     '<img src="'+d.webPath+'"/>' :
 *                     null;
 *             }
 *         }
 *     ],
 *     tableTools: {
 *         sRowSelect: "os",
 *         aButtons: [
 *             { sExtends: "editor_create", editor: editor },
 *             { sExtends: "editor_edit",   editor: editor },
 *             { sExtends: "editor_remove", editor: editor }
 *         ]
 *     }
 * } );
 */

(function($, Editor) {

function uploadFile ( editor, conf, file )
{
    var reader = new FileReader();
    var data = new FormData();
    var ajax;

    reader.onload = function ( e ) {
        data.append( 'action', 'upload' );
        data.append( 'uploadField', conf.name );
        data.append( 'upload', file );

        if ( typeof editor.s.ajax !== 'string' && ! conf.ajax ) {
            throw 'No Ajax option specified for upload plug-in';
        }
        else if ( conf.ajax ) {
            ajax = conf.ajax;
        }
        else {
            ajax = editor.s.ajax;
        }

        if ( typeof ajax === 'string' ) {
            ajax = { url: ajax };
        }

        // Use preSubmit to stop form submission during an upload, since the
        // value won't be known until that point.
        var submit = false;
        editor
            .on( 'preSubmit.DTE_Upload', function () {
                submit = true;
                return false;
            } );

        $.ajax( $.extend( ajax, {
            type: 'post',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            xhrFields: {
                onprogress: function ( e ) {
                    if ( e.lengthComputable ) {
                        buttonText( conf, (e.loaded/e.total*100)+"%" );
                    }
                },
                onloadend: function ( e ) {
                    buttonText( conf );
                }
            },
            success: function ( json ) {
                editor.off( 'preSubmit.DTE_Upload' );

                if ( json.fieldErrors && json.fieldErrors.length ) {
                    var errors = json.fieldErrors;

                    for ( var i=0, ien=errors.length ; i<ien ; i++ ) {
                        editor.error( errors[i].name, errors[i].status );
                    }
                }
                else if ( json.error ) {
                    editor.error( json.error );
                }
                else {
                    Editor.fieldTypes.upload.set.call( editor, conf, json.upload.id, json.upload.row );
                    
                    if ( submit ) {
                        editor.submit();
                    }
                }
            }
        } ) );
    };

    reader.readAsDataURL( file );
}

function buttonText ( conf, text )
{
    if ( text === null || text === undefined ) {
        text = conf.uploadText || "Choose file...";
    }

    conf._input.find('div.upload button').text( text );
}


Editor.fieldTypes.upload = $.extend( true, {}, Editor.models.fieldType, {
    "create": function ( conf ) {
        var that = this;
        var container = $(
            '<div class="editor_upload">'+
                '<div class="eu_table">'+
                    '<div class="row">'+
                        '<div class="cell upload">'+
                            '<button class="DTE_Button" />'+
                            '<input type="file"/>'+
                        '</div>'+
                        '<div class="cell clearValue">'+
                            '<button class="DTE_Button" />'+
                        '</div>'+
                    '</div>'+
                    '<div class="row second">'+
                        '<div class="cell">'+
                            '<div class="drop"><span/></div>'+
                        '</div>'+
                        '<div class="cell">'+
                            '<div class="rendered"/>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'
        );

        conf._input = container;
        conf._enabled = true;

        buttonText( conf );

        if ( window.FileReader && conf.dragDrop !== false ) {
            container.find('div.drop span').text(
                conf.dragDropText || "Drag and drop a file here to upload"
            );

            var dragDrop = container.find('div.drop');
            dragDrop
                .on( 'drop', function (e) {
                    if ( conf._enabled ) {
                        uploadFile( that, conf, e.originalEvent.dataTransfer.files[0] );
                        dragDrop.removeClass('over');
                    }
                    return false;
                } )
                .on( 'dragleave dragexit', function (e) {
                    if ( conf._enabled ) {
                        dragDrop.removeClass('over');
                    }
                    return false;
                } )
                .on( 'dragover', function (e) {
                    if ( conf._enabled ) {
                        dragDrop.addClass('over');
                    }
                    return false;
                } );

            // When an Editor is open with a file upload input there is a
            // reasonable chance that the user will miss the drop point when
            // dragging and dropping. Rather than loading the file in the browser,
            // we want nothing to happen, otherwise the form will be lost.
            that
                .on( 'open', function () {
                    $('body').on( 'dragover.DTE_Upload drop.DTE_Upload', function (e) {
                        return false;
                    } );
                } )
                .on( 'close', function () {
                    $('body').off( 'dragover.DTE_Upload drop.DTE_Upload' );
                } );
        }
        else {
            container.addClass( 'noDrop' );
            container.append( container.find('div.rendered') );
        }

        container.find('div.clearValue button').on( 'click', function () {
            Editor.fieldTypes.upload.set.call( that, conf, '' );
        } );

        container.find('input[type=file]').on('change', function () {
            uploadFile( that, conf, this.files[0] );
        } );

        return container;
    },

    "get": function ( conf ) {
        return conf._val;
    },

    "set": function ( conf, val, data ) {
        conf._val = val;

        var container = conf._input;

        if ( conf.display ) {
            if ( ! data ) {
                data = this._dataSource( 'get', this.modifier(), this.fields() );
            }

            var rendered = container.find('div.rendered')
                .html( conf.display( conf._val, data ) );

            rendered.toggleClass( 'children', rendered[0].childNodes.length !== 0 );
        }

        var button = container.find('div.clearValue button');
        if ( val && conf.clearText ) {
            button.html( conf.clearText );
            container.removeClass( 'noClear' );
        }
        else {
            container.addClass( 'noClear' );
        }
    },

    "enable": function ( conf ) {
        conf._input.find('input').prop('disabled', false);
        conf._enabled = true;
    },

    "disable": function ( conf ) {
        conf._input.find('input').prop('disabled', true);
        conf._enabled = false;
    }
} );

}(jQuery, jQuery.fn.dataTable.Editor));
