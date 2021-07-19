<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');

$_dbh = new _db();
$ordered_list = rvs($_POST['nlist']);
$_db_tbl = rvs($_POST['gen_table']);
$_sort_list_prefix = rvs($_POST['sort_list_prefix']);
$_prefix_len = strlen($_sort_list_prefix);

//_lg($_POST);
$ordered_array = array_filter(explode(',', $ordered_list));
$id_array = [];

foreach($ordered_array as $o){
	$id_array[] = substr($o, $_prefix_len);
}
$err = false;
$_sql = "update ".$_db_tbl." set order_num = :order_num where id = :id";
for($i = 0; $i < count($id_array); $i++){
	$id = $id_array[$i];
	$n = ($i + 1) * 10;
	$_d = array('order_num' => $n, 'id' => $id);
	$_f = array('i', 'i');
	$_result = $_dbh->_update_sql($_sql, $_d, $_f);
	if ($_result){
		$err = false;
	}else{
		$err = true;
	}
}

if ($err == false){
	$_return['status'] = 'success';
	$_return['title'] = 'Order success';
	$_return['message'] = 'The order of the list items has been successfully updated.';
}else{
	$_return['status'] = 'failure';
	$_return['title'] = 'Order failure';
	$_return ['message'] = 'There was a problem ordering the list items. Please try again.';
}

echo json_encode($_return);
?>
