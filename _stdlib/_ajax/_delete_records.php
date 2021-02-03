<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');
//_cl($_POST);
$_dbh = new _db();
$_db_main_tbl = rvs($_POST['db_main_tbl']);
$_db_main_tbl_field = rvs($_POST['db_main_tbl_field']);
$_db_tbl_field_value = rvz($_POST['db_tbl_field_value']);

$_db_sub_tbls = array_filter(json_decode($_POST['db_sub_tbls']));
$_db_sub_tbl_fields = array_filter(json_decode($_POST['db_sub_tbl_fields']));
if (!empty($_db_sub_tbls)){
// Delete the subtable records
	for ($i = 0; $i < count($_db_sub_tbls); $i++){
		$_t = $_db_sub_tbls[$i];
		$_f = $_db_sub_tbl_fields[$i];
		$_sql = "delete from ".$_t." where ".$_f." = :".$_f;
		$_d = array($_f => $_db_tbl_field_value);
		$_f = array('i');
		$_result = $_dbh->_delete_sql($_sql, $_d, $_f);
	}

}
//Finally delete the main tbl records
$_sql = "delete from ".$_db_main_tbl." where ".$_db_main_tbl_field." = :".$_db_main_tbl_field;
$_d = array($_db_main_tbl_field => $_db_tbl_field_value);
$_f = array('i');
$_result = $_dbh->_delete_sql($_sql, $_d, $_f);
echo json_encode($_result);
?>