<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');

$main = rvs($_POST['main']);
$id = rvz($_POST['id']);

$form_type = rvs($_POST['form_type']);
$view_archive = rvz($_POST['view_archive']);
$cfg_title = rvs($_POST['cfg_title']);
$cfg_title_form =  rvs($_POST['cfg_title_form']);

$t = new _topic();
$t->_set_main($main);
$t->_set_id($id);

$_ret = array();
$_ret['topic'] = base64_encode($t->_build_topic());

echo json_encode($_ret);

?>
