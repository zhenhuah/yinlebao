<?php
  //构造PDO连接
//if($_GET['func'] == "operating_receipt"){
        $dbh = "mysql:host=localhost;dbname=go3capi";
        $db = new PDO($dbh, 'root', '86985773');
        $db->query("set character set 'utf8'");
        // $report_id = $_POST['report_id'];
        $sql = "SELECT * FROM `auth_operating_receipt` limit 1";
		echo $sql;
        $sth = $db->query($sql);
        $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        echo json_encode($thc);
        $db = null;      
    //}
        // $dbh = "mysql:host=localhost;dbname=thc_test";
        // $db = new PDO($dbh, 'root', '');
        // $db->query("set character set 'utf8'");

        // //查询数据
        // $sql = "select * from user";
        // $sth = $db->query($sql);
        // $thc = $sth->fetchAll(PDO::FETCH_ASSOC);//关联数组
        // echo json_encode($thc);
        // $db = null;
?>
