<?php
require_once  dirname(__FILE__) . '/bigtvm_common.php' ;
$phpcmsdb = yzy_phpcms_db() ;
$go3cdb = yzy_go3c_db() ;

$columnid = array(5,4);

foreach($columnid as $cid){

$vrs = $go3cdb->r('video',array('column_id'=>$cid)) ;
foreach($vrs as $vv){ 
	
	$vid = $vv['vid'];
	if(strlen($vid)<20 && strpos($vid,'Vodasset')==0){
		echo $vv['vid']."\n";
		//$go3cdb->w('video',array('shared'=>1),array('vid'=>$vv['vid']));
	}else{
		//$go3cdb->w('video',array('shared'=>0),array('vid'=>$vv['vid']));
	}
}

}
?>
