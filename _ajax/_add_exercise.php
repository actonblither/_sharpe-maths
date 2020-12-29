<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);

$_dbh = new _db();

$_sql = "insert into ".$_db_tbl." set topic_id = :topic_id, number_of_questions = 10";
$_d = array('topic_id' => $_topic_id);
$_f = array('i');
$_ex_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);

$_sql = 'insert into _app_topic_ex_q set topic_id = :topic_id, ex_id = :ex_id, display = 1, archived = 0';
$_d = array('topic_id' => $_topic_id, 'ex_id' => $_ex_insert_id);
$_f = array('i');
$_qu_insert_id = $_dbh->_insert_sql($_sql, $_d, $_f);

$_new_ex = new _exercise();
$_new_ex->_set_topic_id($_topic_id);
$_new_ex->_set_topic_ex_id($_ex_insert_id);
$_new_line = $_new_ex->_build_new_exercise($_topic_id, $_ex_insert_id);
$_new_line .= "<ul id = 'exqs".$_ex_insert_id."' class = 'topic_exercise hidden'>";
$_new_line .= $_new_ex->_build_new_question($_topic_id, $_qu_insert_id);
$_new_line .= '</ul>';
echo json_encode($_new_line);
?>