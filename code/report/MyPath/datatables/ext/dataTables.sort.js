var mytitle='Create a new entry';
var thref=window.location.href;
var hpath=thref.substring(0,thref.lastIndexOf('/'))+'/'; 
var init_f_mid=false;
var tmp=0;
var tmpdata=[];

function getparam(){
    var str=window.location.search;   
    offset=str.indexOf("?");
		return str.substr(1,128);
}

function get_filterid()
{
	  var param=getparam();
		var offset=param.lastIndexOf("=");
		return param.substring(offset+1,256);
}


function render_link(data,title){
		var urld=data;
		var note=title;
		if(!data) {
						urld="test" ;
						note="null";
		} 
    return '<a  target="_blank" href="'+hpath.concat(urld)+'">'+note+'</a>';
}

function render_return(data,title){
		var urld=data;
		var note=title;
		if(!data) {
						urld="test" ;
						note="null";
		} 
    return '<a  target="_blank" href="'+hpath+"return_"+urld+'.js">'+note+'</a>';
}


function init_sort()
    	{
    		
    		if(!init_f_mid) return;
    		
    		table.api()
			    .column( kidx )
			    .data()
			    .each( function ( value, index ) {
			    			console.log(index+' '+value)
			           var arr  =
										     {
										         "index" : index,
										         "id" : value,
										     };
			        tmpdata.push(arr);
			        console.log("index="+index+" value="+value);
			    } );
			    
			   queue_next();
			 } 
   
	
  function queue_next(){
  	
  			if(tmpdata.length<=0) return ;
  			//var arr=tmpdata.pop();
  			//while(tmpdata.length>0)
  			{
			 	var arr=tmpdata.pop();
			 	 console.log("arr="+arr['index']+"id="+arr['id']);
			 }
  			editor.edit(arr['index'],false);
				editor.set(get_sid(),arr['id']);
				editor.submit();
			
  }


function  submit_sort_field(idprefix,data,tname1,sid,fid)
{
	
					var tsid;
					var idx;
					console.log(idprefix.concat(typeof(idprefix)));
					//console.log(table.api().column(1,{order:'index'}).data());	
					if (typeof(idprefix)!="undefined") // && typeof(idprefix)!="undefined" && idprefix!=0
					{
							console.log(data);
							console.log(data[tname1][sid]);
							tsid=data[tname1][sid];
							idx=data[tname1][fid];
					}else
					{
							console.log(data[sid]);
							tsid=data[sid];
							idx=data[fid];		
						}
					
					if(tsid==='0' || tsid==null){    
					
				       console.log("fill sort idx="+idx+" tsid="+tsid);	  	
	     	  	/* get the row index by f_table_id then edit this row with fmid=id */
						/*
						console.log(table.api().column(1).data());
						var indexes= table.api()
											    .column( 1 )
											    .data()
											    .lastIndexOf( data['modelid']);		
						     	  	console.log("redit mid for ftid="+ftid+" row="+indexes+"with mid="+data['modelid']);
	     	  	//editor.edit(this,false);
	     	  	*/
	     	  	
	     	  	var indexes = table.api().rows().eq( 0 ).filter( function (rowIdx) {
						    return table.api().cell( rowIdx, kidx ).data() === idx ? true : false;
						} );
						console.log(indexes);
	     	  	editor.edit(indexes[0],false);
	     	  	console.log(get_sid()+parseInt(idx));
	     	 		editor.set(get_sid(),parseInt(idx));
					  editor.submit();  
					}
	
}

function   dataTable_height_padding(oSettings,table){
	
						console.log("DrawCallback"+oSettings._iDisplayLength);
		    		var total_count = oSettings.fnRecordsTotal()<oSettings._iDisplayLength?oSettings._iDisplayLength+1:oSettings.fnRecordsTotal();
		        var columns_in_row = table.children('thead').children('tr').children('th').length;
		        var show_num = oSettings._iDisplayLength;
		        var tr_count = table.children('tbody').children('tr').length;
		        var missing = show_num - tr_count;
		        if (show_num < total_count && missing > 0){
		          for(var i = 0; i < missing; i++){
		            table.append('<tr class="space"><td colspan="' + columns_in_row + '">&nbsp;</td></tr>'); 
		          }
		        }
		        if (show_num > total_count) {
		          for(var i = 0; i < (total_count - tr_count); i++) {
		            table.append('<tr class="space"><td colspan="' + columns_in_row + '">&nbsp;</td></tr>'); 
		          }
		        }
}

		/*	
		table.rowGrouping({   iGroupingColumnIndex:4,
													iGroupingOrderByColumnIndex:5,
													bExpandableGrouping: true ,
													bHideGroupingColumn: false,
													iExpandGroupOffset: 100,
															//bExpandSingleGroup:true,
															// bSetGroupingClassOnTR: false,
															//sEmptyGroupLabel:"No",
															//sGroupLabelPrefix: "Group ",
															//sGroupLabelPrefix2:"Group-" ,
													//iGroupingColumnIndex2:6,
															 fnGroupLabelFormat: function (label) { return label['name'] }
															});
     
     
		table.rowGrouping({   iGroupingColumnIndex:4,
													iGroupingOrderByColumnIndex:5,
													bExpandableGrouping: true ,
													bHideGroupingColumn: false,
													iExpandGroupOffset: 100,
															//bExpandSingleGroup:true,
															// bSetGroupingClassOnTR: false,
															//sEmptyGroupLabel:"No",
															//sGroupLabelPrefix: "Group ",
															//sGroupLabelPrefix2:"Group-" ,
													//iGroupingColumnIndex2:6,
															 fnGroupLabelFormat: function (label) {
															 	console.log(label);
															 	 return label['name'] }
															});
															*/
     
     	/*	table.columnFilter({aoColumns:[
                       { type:"select", sSelector: "#renderingEngineFilter" },
                       { sSelector: "#browserFilter" },
                       { sSelector: "#platformsFilter" },
                       { type:"number-range", sSelector: "#engineVersionFilter" },
                       { type:"select", values : ["A", "B", "C", "X"], sSelector: "#cssGradeFilter" },
                       { type:"select", values : ["vannkorn", "ravy", "rayuth"], sSelector: "#customFilter"}    //here is where I add it
                       ]}
                   );*/
                   
                   			/*
			table.api().column( 0 )
			    .data()
			    .filter( function ( value, index ) {
			    	  console.log(value);
			        return value > 20 ? true : false;
			    } );*/
			
			/*columnFilter({
					    sPlaceHolder: "head:before",
              aoColumns: [null, null, null, null, null, { type: "date-range", sRangeFormat: "Between {from} and {to} dates" }]
			});*/
			/* used for fixed width filter
			function fnCreateInput(regex, smart, bIsNumber) {
        var sCSSClass = "text_filter";
        if (bIsNumber)
            sCSSClass = "number_filter";
        var input = $('<input type="text" class="search_init ' + sCSSClass + '" value="' + label + ' style="width:100px" />');
			*/
			
			/*
	$(tname2).on( 'click', 'td.details-control', function () {
        var tr = this.parentNode;
        
        console.log(table);
  
  //      if ( table.row( tr ).child.isShown() ) { //this line used for Datatable
   			if ( table.fnIsOpen( tr ) ) {
            editor.close();
        }
        else {
            editor.edit(
                tr,
                'Edit row',
                [
                    {
                        "className": "delete",
                        "label": "Delete row",
                        "fn": function () {
                            // Close the edit display and delete the row immediately
                            editor.close();
                            editor.remove( tr, '', null, false );
                            editor.submit();
                        }
                    }, {
                        "label": "Update row",
                        "fn": function () {
                            editor.submit();
                        }
                    }
                ]
            );
        }
    } );   */    

/*
	var wrapper = $('#resize_wrapper');
	$('#resize_handle').on( 'mousedown', function (e) {
		var mouseStartY = e.pageY;
		var resizeStartHeight = wrapper.height();

		$(document)
			.on( 'mousemove.demo', function (e) {
				var height = resizeStartHeight + (e.pageY - mouseStartY);
				if ( height < 180 ) {
					height = 180;
				}

				wrapper.height( height );
			} )
			.on( 'mouseup.demo', function (e) {
				$(document).off( 'mousemove.demo mouseup.demo' );
			} );

		return false;
	} );
*/


     
      	/*
      	
      	Editor.display.jqueryui.modalOptions = {
        		width: 600,
        		modal: true
    		};
    		Editor.display.jqueryui.modalOptions = {
        		width: 600,
        		modal: true
    		};
    		Editor.display.jqueryui = $.extend( true, {}, Editor.models.displayController, {
      	     
      	
        "init": function ( dte ) {
            dte.__dialouge = $('<div></div>')
                .css('display', 'none')
                .appendTo('body')
                .dialog( $.extend( true, Editor.display.jqueryui.modalOptions, {
                    autoOpen: false,
                    buttons: { "A": function () {} } // fake button so the button container is created
                } ) );
 
            // Need to know when the dialogue is closed using its own trigger
            // so we can reset the form
            $(dte.__dialouge).on( 'dialogclose', function (e) {
                dte.close('icon');
            } );
 
            $(dte.dom.formError).appendTo(
                dte.__dialouge.parent().find('div.ui-dialog-buttonpane')
            );
 
            return Editor.display.jqueryui;
        },
 
        "open": function ( dte, append, callback ) {
            dte.__dialouge
                .append( append )
                .dialog( 'open' );
 
            console.log( dte.__dialouge );
            dte.__dialouge.parent().find('.ui-dialog-title').html( dte.dom.header.innerHTML );
 
            // Modify the Editor buttons to be jQuery UI suitable
            var buttons = $(dte.dom.buttons)
                .children()
                .addClass( 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' )
                .each( function () {
                    $(this).wrapInner( '<span class="ui-button-text" />' );
                } );
 
            // Move the buttons into the jQuery UI button set
            dte.__dialouge.parent().find('div.ui-dialog-buttonset')
                .empty()
                .append( buttons );
 
            if ( callback ) {
                callback();
            }
        },
 
        "close": function ( dte, callback ) {
            if ( dte.__dialouge ) {
                dte.__dialouge.dialog( 'close' );
            }
 
            if ( callback ) {
                callback();
            }
        }
    } );
 
 		Editor.display.jqueryui.modalOptions = {
        		width: 600,
        		modal: true
    		};
 */
 
 	var Editor = $.fn.dataTable.Editor;
	
      Editor.display.details = $.extend( true, {}, Editor.models.displayController, {
      	
      	  "init": function ( editor ) {
        // No initialisation needed - we will be using DataTables' API to display items
        return Editor.display.details;
    },
  
    "open": function ( editor, append, callback ) {
        var table = $(editor.s.table).DataTable();
        var row = editor.s.modifier;
  
        // Close any rows which are already open
        Editor.display.details.close( editor );
  
        // Open the child row on the DataTable
        table
            .row( row )
            .child( append )
            .show();
 
        $( table.row( row ).node() ).addClass( 'shown' ); /*used for DataTable*/
 			 // $( table.fnGetNodes( row )).addClass( 'shown' );
        if ( callback ) {
            callback();
        }
    },
  
    "close": function ( editor, callback ) {
        var table = $(editor.s.table).DataTable();
 
        table.rows().every( function () {
            if ( this.child.isShown() ) {
                this.child.hide();
                $( this.node() ).removeClass( 'shown' );
            }
        } );
  
        if ( callback ) {
            callback();
        }
    }
   } );
   
    