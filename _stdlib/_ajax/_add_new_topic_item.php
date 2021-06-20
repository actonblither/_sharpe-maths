<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_item_name = rvs($_POST['item_name']);
$_item_class_name = rvs($_POST['item_class_name']);
$_topic_id = rvz($_POST['topic_id']);
$_admin_template = rvs($_POST['admin_template']);
$_field_prefix = rvs($_POST['field_prefix']);


//_lg($_POST);
$_dbh = new _db();
$_tf = $_field_prefix.'title';
$_sql = "insert into ".$_db_tbl." set ".$_tf." = :title, topic_id = :topic_id";
$_d = array('title' => 'new entry', 'topic_id' => $_topic_id);
$_f = array('s', 'i');
$_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);

$_new_item = new $_item_class_name($_topic_id);
$_new_line = $_new_item->_fetch_template($_admin_template, array('_item_id' => $_insert_id));

echo json_encode($_new_line);
?>