


function getsave(id){
	var f_cat_parent_id = 'common data';
	url='./php/go3co2o.php?m=getcatliststatus&f_cat_parent_id='+f_cat_parent_id+'&id='+id;
	$.ajax({
		url: url,
		type : 'GET',
		dataType : 'json',
		success: function (backdata) {
			if (backdata != ' ') {
				alert("插入成功!");
				collectionajax();
				//oTable.fnReloadAjax();
			} else {
				alert("插入失败!");
			}
		}, error: function (error) {
			console.log(error);
		}
	});
}
