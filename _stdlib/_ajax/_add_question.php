<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);
$_ex_id = rvz($_POST['ex_id']);
//_lg($_POST);
$_dbh = new _db();

$_sql = "insert into ".$_db_tbl." set topic_id = :topic_id, ex_id = :ex_id";
$_d = array('topic_id' => $_topic_id, 'ex_id' => $_ex_id);
$_f = array('i', 'i');
$_ex_insert_id = (int) $_dbh->_insert_sql($_sql, $_d, $_f);

$_new_q = new _exercise($_topic_id);
$_tpl = __s_app_folder__.'_classes/_templates/_admin_exercise_sub_tpl.txt';
$_r['id'] = $_ex_insert_id;
$_r['topic_id'] = $_topic_id;
$_new_line = $_new_q->_fetch_template($_tpl, $_r);

echo json_encode($_new_line);
?>