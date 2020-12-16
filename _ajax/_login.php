<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');


$_un = rvs($_POST['un']);
$_pw = rvs($_POST['pw']);

$_login = new _login();
$_login->_set_un($_un);
$_login->_set_pw($_pw);
$_auid = $_login->_login();
if ($_auid){
	$_return['message'] = '';
	$_return['status'] = 1;
	$_SESSION['s_is_logged_in'] = 1;
	$_SESSION['s_auid'] = $_auid;
}else{
	$_return['status'] = 0;
	$_return['message'] = 'The details entered were not recognised. Please try again.';
}

echo json_encode($_return);
?>