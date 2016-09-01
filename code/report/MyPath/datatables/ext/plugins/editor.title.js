$.fn.dataTable.Editor.fieldTypes.title = $.extend( true, {}, $.fn.dataTable.Editor.models.fieldType, {
    create: function ( field )      { return $('<div/>')[0]; },
    get:    function ( field )      { return ''; },
    set:    function ( field, val ) {}
} );