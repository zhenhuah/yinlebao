<?php 
echo '11';
/*添加*/  
// //$sql = "INSERT INTO `user` SET `login`=:login AND `password`=:password";   
// $sql = "INSERT INTO `user` (`login` ,`password`)VALUES (:login, :password)";  
//$stmt = $dbh->prepare($sql);  $stmt->execute(array(':login'=>'kevin2',':password'=>''));    
// echo $dbh->lastinsertid();    
// /*修改*/  
// $sql = "UPDATE `user` SET `password`=:password WHERE `user_id`=:userId";    
// $stmt = $dbh->prepare($sql);    
// $stmt->execute(array(':userId'=>'7', ':password'=>'4607e782c4d86fd5364d7e4508bb10d9'));    
// echo $stmt->rowCount();   
// /*删除*/  
// $sql = "DELETE FROM `user` WHERE `login` LIKE 'kevin_'"; //kevin%    
// $stmt = $dbh->prepare($sql);    
// $stmt->execute();    
// echo $stmt->rowCount();    
// /*查询*/  
// $login = 'kevin%';    
// $sql = "SELECT * FROM `user` WHERE `login` LIKE :login";    
// $stmt = $dbh->prepare($sql);    
// $stmt->execute(array(':login'=>$login));    
// while($row = $stmt->fetch(PDO::FETCH_ASSOC)){       
//  print_r($row);    
// }    
// print_r( $stmt->fetchAll(PDO::FETCH_ASSOC));  


	if($_POST['func'] == 'insert_thead'){
		$dbh = "mysql:host=localhost;dbname=thc_test";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        $report_id = $_POST['report_id'];
        $sql = 'DELETE FROM `custom_report_format` WHERE `report_id` =  "'.$report_id.'" and `position` =  "thead"';    
        $stmt = $db->prepare($sql);    
        $stmt->execute();    
        // echo json_encode(array('message' =>"$sql",'status'=>'error'));
        // return false;
        // $position = $_POST['position'];
        // $col = $_POST['col'];
        // $font = $_POST['font'];
        // $align = $_POST['align'];
        // $font_color = $_POST['font_color'];
        // $font_size = $_POST['font_size'];
        // $font_weight = $_POST['font_weight'];
        // $title = $_POST['title'];
        // $width = $_POST['width'];
        //插入
        $sql = 'INSERT INTO `custom_report_format` (`width`, `position`, `report_id`, `col`,    `font`, `align`, `font_color`, `font_size`, `font_weight`, `title`)
        VALUES';
        foreach($_POST['data'] as $k=>$v){
            $position = $v['position'];
            $col = $v['col'];
            $font = $v['font'];
            $align = $v['align'];
            $font_color = $v['font_color'];
            $font_size = $v['font_size'];
            $font_weight = $v['font_weight'];
            $title = $v['title'];
            $width = $v['width'];
            if($k==0){
                $sql .= '("'.$width.'", "'.$position.'", "'.$report_id.'", "'.$col.'", "'.$font.'", "'.$align.'", "'.$font_color.'", "'.$font_size.'", "'.$font_weight.'", "'.$title.'")';
            }
            else{
                $sql .= ',("'.$width.'", "'.$position.'", "'.$report_id.'", "'.$col.'", "'.$font.'", "'.$align.'", "'.$font_color.'", "'.$font_size.'", "'.$font_weight.'", "'.$title.'")';
            }
        }
  		$count = $db->exec($sql);
        if($db->lastinsertid()){
        	echo json_encode(array('message' =>"添加成功表头!",'status'=>'success'));
        }
        else{
        	echo json_encode(array('message' =>"添加失败!",'status'=>'error'));
        }
        // $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        $db = null;
	}
    else if($_POST['func'] == 'insert_tbody'){
        $dbh = "mysql:host=localhost;dbname=thc_test";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        $report_id = $_POST['report_id'];
        $sql = 'DELETE FROM `custom_report_format` WHERE `report_id` =  "'.$report_id.'" and `position` =  "tbody"';   
        $stmt = $db->prepare($sql);    
        $stmt->execute();    
        //插入
        $sql = 'INSERT INTO `custom_report_format` (`filter`,`data_type`,`title`,`width`,`position`, `report_id`, `col`, `font`, `align`, `font_color`, `font_size`, `font_weight`, `orderable`, `dataSource`, `dataField`)
        VALUES';
        foreach($_POST['data'] as $k=>$v){
            $position = $v['position'];
            $col = $v['col'];
            $font = $v['font'];
            $align = $v['align'];
            $font_color = $v['font_color'];
            $font_size = $v['font_size'];
            $font_weight = $v['font_weight'];
            $orderable = $v['orderable'];
            $dataSource = $v['dataSource'];
            $dataField = $v['dataField'];
            $width = $v['width'];
            $title = $v['title'];
            $filter = $v['filter'];
            $data_type = $v['data_type'];
            if($k == 0){
                $sql .= '("'.$filter.'", "'.$data_type.'", "'.$title.'", "'.$width.'", "'.$position.'", "'.$report_id.'", "'.$col.'", "'.$font.'", "'.$align.'", "'.$font_color.'", "'.$font_size.'", "'.$font_weight.'", "'.$orderable.'", "'.$dataSource.'", "'. $dataField.'")';
            }
            else{
                $sql .= ',("'.$filter.'", "'.$data_type.'", "'.$title.'", "'.$width.'", "'.$position.'", "'.$report_id.'", "'.$col.'", "'.$font.'", "'.$align.'", "'.$font_color.'", "'.$font_size.'", "'.$font_weight.'", "'.$orderable.'", "'.$dataSource.'", "'. $dataField.'")';
            }
        }
         $count = $db->exec($sql);
        if($db->lastinsertid()){
            echo json_encode(array('message' =>"添加成功表体!",'status'=>'success'));
        }
        else{
            echo json_encode(array('message' =>"添加失败!",'status'=>'error'));
        }
        // $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        $db = null;
    }
    else if($_REQUEST['func'] == 'get_data'){
        $dbh = "mysql:host=localhost;dbname=go3capi";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        $report_id = $_REQUEST['report_id'];
        $sql = "SELECT * FROM `auth_report_format` WHERE `report_id` = $report_id order by col asc";
        $sth = $db->query($sql);
        $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        //$sql = "SELECT * FROM `user` WHERE `report_id` = :report_id";    
        //$stmt = $db->prepare($sql);    
        //$stmt->execute(array(':report_id'=>$report_id));
        //$stmt->fetch(PDO::FETCH_ASSOC)
        echo json_encode(array('message' =>$thc,'status'=>'success'));
        $db = null;
    }
    else if($_POST['func'] == "operating_receipt"){
        $dbh = "mysql:host=localhost;dbname=go3capi";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        // $report_id = $_POST['report_id'];
        //$sql = "SELECT total,date,drink,heat_food FROM `operating_receipt`";
        $sql = "SELECT * FROM `operating_receipt`";
        $sth = $db->query($sql);
        $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        //echo json_encode($thc);
        echo json_encode(array('message' =>$thc,'status'=>'success'));
        $db = null;      
    }
    else if($_POST['func'] == "refresh_operating_receipt"){
        $dbh = "mysql:host=localhost;dbname=go3capi";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        // $report_id = $_POST['report_id'];
        $sql = 'SELECT * FROM `operating_receipt` where `date` >= "'.$_POST['start'].'" and `date` <= "'.$_POST['end'].'"';
        $sth = $db->query($sql);
        //echo $sql;
        $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        echo json_encode(array('message' =>$thc,'status'=>'success'));
        $db = null;   
    }
?>
