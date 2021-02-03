<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');
//_cl($_POST);

$_id = rvz($_POST['id']);
$_main = rvs($_POST['main']);

$_return['page'] = '';
if ($_main === 'page'){
	if ($_id == 4){
		$g = new _puzzle($_id);
		$_return['page'] .= $g->_fetch_all_puzzles();
	}else{
		$h = new _pages($_id);
		$h->_set_main($_main);
		$_return['page'] .= $h->_build_page();
	}
}else if ($_main === 'topic'){
	if ($_id === 5){
		$h = new _glossary();
		$_return['page'] .= $h->_build_glossary();
	}else{
		$h = new _topic(false);
		$h->_set_main($_main);
		$h->_set_topic_route_id($_id);
		$h->_set_topic_order_array($_SESSION['s_topic_order']);
		$_return['page'] .= $h->_build_topic();
	}
}
echo json_encode($_return);
?>