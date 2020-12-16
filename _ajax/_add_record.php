<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);
$_ex_id = rvz($_POST['ex_id']);

$_dbh = new _db();

$_sql = "insert into ".$_db_tbl." set topic_id = :topic_id, ex_id = :ex_id";
$_d = array('topic_id' => $_topic_id, 'ex_id' => $_ex_id);
$_f = array('i', 'i');
$_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);
$_new_line = array();
$_new_line['code'] = "<li id = 'ex".$_insert_id."'></li>";
$_new_line['status'] = rvz($_insert_id);

echo json_encode($_new_line);
?>