
var editoradd; // use a global for the submit and return data rendering in the examples

// Template function to display the information panels. Editor will
// automatically keep the values up-to-date with any changes due to the use of
// the `data-editor-field` attribute. It knows which panel to update for each
// record through the use of `data-editor-id` in the container element.
function createPaneladd ( data )
{
	var id = data.DT_RowId;
	
	$(
		'<div class="panel" data-editor-id="'+id+'">'+
			'<i class="edit fa fa-pencil" data-id="'+id+'"/>'+
			'<i class="remove fa fa-times" data-id="'+id+'"/>'+
			'<div class="portlet">'+
			'<dl class="portlet-header">'+
				'<dt>Name:</dt>'+
				'<dd>'+
					'<span data-editor-field="f_cat_name">'+data.f_cat_name+'</span> '+
				'</dd>'+
				'<dt>cat_id:</dt>'+
				'<dd data-editor-field="f_cat_id">'+data.f_cat_id+'</dd>'+
				'<dt>edit_link:</dt>'+
				'<dd data-editor-field="f_edit_link" onclick="getdel('+data.id+')">删除</dd>'+
			'</dl>'+
			'</div>'+
		'</div>'
	).appendTo( '#panelsadd' );
}

$(document).ready(function() {
	editoradd = new $.fn.dataTable.Editor( {
		ajax: "./php/table.staffor1.php",
		fields: [ {
				label: "id:",
				name: "id"
			}, {
				label: "f_cat_name:",
				name: "f_cat_name"
			}, {
				label: "f_cat_id:",
				name: "f_cat_id"
			}
		]
	} );

	// Create record - on create we insert a new panel
	editoradd.on( 'postCreate', function (e, json) {
		createPaneladd( json.row );
	} );

	/*$('button.create').on( 'click', function () {
		editoradd
			.title('Create new record')
			.buttons('Create')
			.create();
	} );
*/
	// Edit
	$('#panelsadd').on( 'click', 'i.edit', function () {
		editoradd
			.title('Edit record')
			.buttons('Save changes')
			.edit( $(this).data('id') );
	} );	

	// Remove
	$('#panelsadd').on( 'click', 'i.remove', function () {
		/*editor
			.create( false )
			.val('f_cat_for',$(this).data('f_cat_for'))
			.val('f_cat_parent_id',$(this).data('f_cat_parent_id'))
			.val('f_cat_name',$(this).data('f_cat_name'))
			.val('id',$(this).data('id'))
			.submit();
			
		var f_cat_id = $(this).data('f_cat_id');
		var f_cat_name = $(this).data('f_cat_name');
		var id = $(this).data('id');
		*/
		/*
		var f_cat_for = editoradd.field('f_cat_for');
		var f_cat_parent_id = editoradd.field('f_cat_parent_id');
		var f_cat_name = editoradd.field('f_cat_name');
		var id = editoradd.field('id');
		*/
		editoradd
			.title('Delete record')
			.buttons('Delete')
			.message('Are you sure you wish to deleteadd this record?'+$(this).data('id'))
			.remove( $(this).data('id') );
/*		
		fname
			.val(f_cat_for);
		fname
			.val(f_cat_parent_id);
		fname
			.val(f_cat_name);	
		fname
			.val(id);
*/			
		editor
			 .edit( null, false )
			//.edit( false )
			//.create( false )
//			.val('f_cat_for','11')
//			.val('f_cat_parent_id','22')
			.val('f_cat_id',32)
			.val('id',35)
			.submit();
	} );
	/*
	editoradd.on('open',function(e,type){
		alert('editoradd open'+type);
	});
	editoradd.on('submitSuccess',function(e,type){
		alert('editoradd submitSuccess'+type);
	});
	editoradd.on('create',function(e,type){
		alert('editoradd create'+type);
	});
	editoradd.on('postCreate',function(e,type){
		alert('editoradd postCreate'+type);
	});
	editoradd.on('preCreate',function(e,type){
		alert('editoradd preCreate'+type);
	});
	*/
	// Load the initial data and display in panels
	collectionajax();
} );
function getdelte(id,f_cat_name,f_cat_id){
	alert(id);alert(f_cat_id);
}
function collectionajax(){
	$.ajax( {
		url: './php/table.staffor1.php',
		dataType: 'json',
		success: function ( json ) {
			for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
				createPaneladd( json.data[i] );
			}
		}
	} );
}