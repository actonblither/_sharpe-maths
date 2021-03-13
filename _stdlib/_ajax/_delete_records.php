<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');
//_cl($_POST);
$_dbh = new _db();
$_main_db_tbl = rvs($_POST['main_db_tbl']);
$_main_db_tbl_field = rvs($_POST['main_db_tbl_field']);
$_main_db_tbl_field_value = rvz($_POST['main_db_tbl_field_value']);

$_sub_db_tbls = array_filter(json_decode($_POST['sub_db_tbls']));
$_sub_db_tbl_fields = array_filter(json_decode($_POST['sub_db_tbl_fields']));
if (!empty($_sub_db_tbls)){
// Delete the subtable records
	for ($i = 0; $i < count($_sub_db_tbls); $i++){
		$_t = $_sub_db_tbls[$i];
		$_f = $_sub_db_tbl_fields[$i];
		$_sql = "delete from ".$_t." where ".$_f." = :".$_f;
		$_d = array($_f => $_main_db_tbl_field_value);
		$_f = array('i');
		$_result = $_dbh->_delete_sql($_sql, $_d, $_f);
	}
}
//Finally delete the main tbl records
$_sql = "delete from ".$_main_db_tbl." where ".$_main_db_tbl_field." = :".$_main_db_tbl_field;
$_d = array($_main_db_tbl_field => $_main_db_tbl_field_value);
$_f = array('i');
$_result = $_dbh->_delete_sql($_sql, $_d, $_f);
//_cl($_sql);
//_cl($_d);
echo json_encode($_result);
?>