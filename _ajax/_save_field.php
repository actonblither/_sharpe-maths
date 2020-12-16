<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');
//_cl($_POST);

$_link = rvb($_POST['link']);
$_id = rvz($_POST['id']);
$_field = rvs($_POST['field']);
$_db_tbl = rvs($_POST['db_tbl']);
$_topic_id = rvz($_POST['topic_id']);
$_value = rvs($_POST['value']);

$_ex_id = rvz($_POST['ex_id']);

$_dbh = new _db();

if ($_link == false){
	$_sql = "update ".$_db_tbl." set ".$_field." = :".$_field." where id = :id";
	$_d = array($_field => $_value, 'id' => $_id);
	$_f = array('s', 'i');
	$_result = $_dbh->_update_sql($_sql, $_d, $_f);
	if ($_result){
		$_return['status'] = 'success';
		$_return['title'] = 'Save success';
		$_return['message'] = 'The field ('.$_field.') has been successfully updated.';
	}else{
		$_return['status'] = 'failure';
		$_return['title'] = 'Save failure';
		$_return ['message'] = 'There was a problem updating the field ('.$_field.'). Please try again.';
	}
}else{
	$_val = explode(',', $_value);
	$_t = $_db_tbl;
	$_d = array('id_2' => $_id);
	$_f = array('i');
	$_result = $_dbh->_delete($_t, $_d, $_f);
	$_d = array('id_1' => $_id);
	$_result = $_dbh->_delete($_t, $_d, $_f);

	if (!empty($_val)){
		foreach ($_val as $_v){

			$_d = array('id_1' => $_id, 'id_2' => $_v);
			$_f = array('i', 'i');
			if ($_v > 0){
				$_insert_id = $_dbh->_insert($_t, $_d, $_f);
			}
			if ($_insert_id){
				$_return['status'] = 'success';
				$_return['title'] = 'Save success';
				$_return['message'] = 'The connection has been successfully made.';
			}else{
				$_return['status'] = 'failure';
				$_return['title'] = 'Save failure';
				$_return ['message'] = 'There was a problem making the connection. Please try again.';
			}
		}
	}
}
echo json_encode($_return);
?>