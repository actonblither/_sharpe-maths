<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_id = rvz($_POST['id']);
//_lg($_POST);
$_dbh = new _db();
$_sql = "delete from ".$_db_tbl." where id = :id";
$_d = array('id' => $_id);
$_f = array('i');
if ($_dbh->_delete_sql($_sql, $_d, $_f)){
	$_res = 'good';
}else{
	$_res = 'bad';
}

echo json_encode($_res);
