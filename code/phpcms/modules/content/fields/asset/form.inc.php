	function asset($field, $value, $fieldinfo) {
		//如果asset为空，自动生成一个asset_id
		if(!$value){
			if(!trim($_GET['asset_id'])){
				$value = 'Vodasset'.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
			}else{
				$value = trim($_GET['asset_id']);
			}
		}
		//return '<input type="hidden" name="info['.$field.']" value="'.$value.'" size="30">'.$value;
		return '<input type="text" name="info['.$field.']" value="'.$value.'" size="30">';
	}
