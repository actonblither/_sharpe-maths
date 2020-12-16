<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');

$_dbh = new _db();
$ordered_list = rvs($_POST['nlist']);
$_t = rvs($_POST['gen_table']);

$ordered_array = array_filter(explode(',', $ordered_list));
$id_array = [];

foreach($ordered_array as $o){
	$id_array[] = substr($o, 2);
}
$_sql = "update ".$_t." set order_num = :order_num where id = :id";
for($i = 0; $i < count($id_array); $i++){
	$id = $id_array[$i];
	$n = ($i + 1) * 10;
	$_d = array('order_num' => $n, 'id' => $id);
	$_f = array('i', 'i');
	$_result = $_dbh->_update_sql($_sql, $_d, $_f);
	if ($_result){
		echo $id.' ';
	}else{
		echo 'fail';
	}
}
?>
