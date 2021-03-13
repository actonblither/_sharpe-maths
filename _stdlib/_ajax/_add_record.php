<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_main_db_tbl = rvs($_POST['main_db_tbl']);
$_item_class = '_example';
$_topic_id = rvz($_POST['topic_id']);
$_sub_db_tbls = array_filter(json_decode($_POST['sub_db_tbls']));
$_sub_db_tbl_fields = array_filter(json_decode($_POST['sub_db_tbl_fields']));

$_dbh = new _db();

$_sql = "insert into ".$_main_db_tbl." set topic_id = :topic_id";
$_d = array('topic_id' => $_topic_id);
$_f = array('i');

$_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);

if (!empty($_sub_db_tbls)){
	// Insert the subtable records
	for ($i = 0; $i < count($_sub_db_tbls); $i++){
		$_t = $_sub_db_tbls[$i];
		$_f = $_sub_db_tbl_fields[$i];
		$_sql = 'insert into '.$_t.' set topic_id = :topic_id, '.$_f.' = :'.$_f.', display = 1, archived = 0';
		$_d = array('topic_id' => $_topic_id, $_f => $_insert_id);
		$_f = array('i');
		$_result = $_dbh->_delete_sql($_sql, $_d, $_f);
	}
}

$_new_item = new $_item_class($_topic_id);
$_new_line = $_new_item->_build_new_item($_insert_id);

echo json_encode($_new_line);
?>