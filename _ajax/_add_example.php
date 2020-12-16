<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);

$_dbh = new _db();

$_sql = "insert into _app_topic_eg set topic_id = :topic_id";
$_d = array('topic_id' => $_topic_id);
$_f = array('i');
$_eg_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);



$_new_ex = new _topic(false);
$_new_ex->_set_topic_id($_topic_id);
$_new_ex->_set_topic_ex_id($_eg_insert_id);
$_new_line = $_new_ex->_build_new_example($_topic_id, $_eg_insert_id);
$_new_line .= "<ul id = 'egqs".$_eg_insert_id."' class = 'topic_example hidden'>";
$_new_line .= '</ul>';
echo json_encode($_new_line);
?>