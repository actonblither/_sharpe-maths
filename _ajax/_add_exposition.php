<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);

$_dbh = new _db();

$_sql = "insert into _app_topic_exp set topic_id = :topic_id";
$_d = array('topic_id' => $_topic_id);
$_f = array('i');
$_exp_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);


echo json_encode($_exp_insert_id);
?>