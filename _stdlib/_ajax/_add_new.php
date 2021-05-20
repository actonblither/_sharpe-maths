<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');

$_db_tbl = rvs($_POST['db_tbl']);

//_lg($_POST);
$_dbh = new _db();

if ($_db_tbl == '_app_nav_routes'){
	$_t = new _navmenu();
	$_return['page'] = $_t->_build_empty_template();
}

if ($_db_tbl == '_app_topic'){
	$_t = new _topic();
	$_return['page'] = $_t->_build_empty_template();
}

if ($_db_tbl == '__sys_pages'){
	$_t = new _pages();
	$_return['page'] = $_t->_build_empty_template();
}
echo json_encode($_return);
?>