	
/*
 * Editor client script for DB table t_catlist
 * Created by http://editor.datatables.net/generator
 */
 

var editor;
var table;
var kidx=1;// key ID 
var sidx=2;// used for sort
var gidx=5;// used for group
var oidx=2;// used for order
var mytitle='Create a new entry';

var tname1='page';
var tname2='#'+tname1;
//var ajaxsrc='php/table.'+tname1+'.php?'+getparam();
var ajaxsrc='customPage_data.php';
		//	      {
      //           "url":"customPage_data.php",
      //         },
var sid="sort";//要排序的id
var fid='id';
var idprefix=tname1+'.';
var filterid=get_filterid();
var eAction;;

//var init_f_mid=true; // Don't enable this only when you understand why we need this clearly

function get_fid()
{
	 return idprefix.concat(fid);//拼接
}

function get_sid()
{
	 //return idprefix.concat(sid);//拼接
	 return sid;
} 

// (function($){


// $.fn.dataTable.ext.search.push(
//     function( settings, data, dataIndex ) {
    	
//         var min = parseInt( $('#min').val(), 10 );
//         var max = parseInt( $('#max').val(), 10 );
//         var age = parseFloat( data[3] ) || 0; // use data for the age column
 
//         if ( ( isNaN( min ) && isNaN( max ) ) ||
//              ( isNaN( min ) && age <= max ) ||
//              ( min <= age   && isNaN( max ) ) ||
//              ( min <= age   && age <= max ) )
//         {
//             return true;
//         }
//         return false;
//     }
//     console.log(filterid);
//     if(filterid =="Undefined" || !filterid) return true;
    
//     if(data[6]==filterid){
//     	return true;    	
//     }else  return false;
//   }
// );


$(document).ready(function() {
	//alert(get_sid());
	table=$(tname2).dataTable( {
		"dom": "Tfrtip",
		"ajax": ajaxsrc,
		//"scrollCollapse": false,
	  	//"scrollY": 600,
    	"paging": false,
		"columns": [
		{
			data:           null,
			className:      'details-control',
			orderable:      false,               
			defaultContent: ''
      	},
		{
			"data":  "id",
			"bVisible":false
		},
		{
			"data": get_sid(),
			"bVisible":false
		},
		{
			"data": "ueid",
		},
		{
			"data": "alias"
		}
			// {
			// 	"data": "parentid", 
			// 	"render": function ( val, type, row ) {
			// 							//console.log(row);// val=parentid
			// 							//console.log(row['parentid']['name']);
																		
			// 							if(row['parentid']['name']==null) {
			// 						//		console.log(row['v9_menu']['parentid']);
			// 								if(row[tname1]['parentid']==-1)		return "Top cat";
			// 								else return  'Unknown'+row[tname1]['parentid'];
			// 							}
   //                  else return row['parentid']['name'];
   //              },
   //      defaultContent: ""
						
			// },
		],
		  //"order":[oidx,'asc'],
		 "initComplete": function( settings, json ) {
    		//	$('div.loading').remove();
    		// console.log('initComplete '+filterid);
    		
    		// if(filterid !=  "undefined")
    		// 	table.api().columns( idprefix+"parentid").data().search(filterid ).draw();
    		init_sort();
    	//	console.log(table.api().order())
    		
 		 },
	} ).rowReordering({
				sURL:"php/lib/Database/updateOrder2.php",
				sRequestType: "GET",
				sTable:tname1,
				iIndexColumn:sidx,//sidx,
				bGroupingUsed: false,





			// 'callback': function(){updateOrder();},
			//	fnAlert: function(message) {
					//oState.iCurrentPosition, oState.iNewPosition, oState.sDirection
					//alert("order"+table.sSelector);
			//		console.log(table.rowReordering.sSelector);
			//	}
			});
			
   
      // Edit record
    $(tname2).on( 'click', 'a.editor_create', function (e) {
        e.preventDefault();
 				console.log(this);
        editor
            .title(mytitle+"test")
            .buttons( { "label": "add", "fn": function () { editor.submit() } } )
            .create();
    } ); 
	
} );

// }(jQuery));

