<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include_once($_base_folder.'app_config.php');
//_lg($_POST);

$_link_type = rvs($_POST['link_type']);
//_lg($_link_type);
if ($_link_type == 'link'){
	$_id = rvz($_POST['id']);
	$_main = rvs($_POST['main']);

	$_SESSION['s_history'][$_SESSION['s_history_pos']+1]['main'] = $_main;
	$_SESSION['s_history'][$_SESSION['s_history_pos']+1]['id'] = $_id;
	$_SESSION['s_history_end_pos'] = count($_SESSION['s_history'])-1;
	$_SESSION['s_history_pos'] = $_SESSION['s_history_end_pos'];
	//_lg($_SESSION['s_history_pos'], 'HISTORY POS LINK');
}else if ($_link_type == 'back'){
	if ($_SESSION['s_history_pos'] > 0){
		$_SESSION['s_history_pos']--;
		//_lg($_SESSION['s_history_pos'], 'HISTORY POS BACK');
		$_id = $_SESSION['s_history'][$_SESSION['s_history_pos']]['id'];
		$_main = $_SESSION['s_history'][$_SESSION['s_history_pos']]['main'];
	}else{
		die();
	}
}else if ($_link_type == 'forward'){
	if ($_SESSION['s_history_pos'] < $_SESSION['s_history_end_pos']){
		$_SESSION['s_history_pos']++;
		//_lg($_SESSION['s_history_pos'], 'HISTORY POS FORWARD');
		$_id = $_SESSION['s_history'][$_SESSION['s_history_pos']]['id'];
		$_main = $_SESSION['s_history'][$_SESSION['s_history_pos']]['main'];
	}else{
		die();
	}
}

//_lg($_id, 'CURRENT ID VALUE');
//_lg($_main, 'CURRENT MAIN VALUE');
$_dbh = new _db();

$_SESSION['s_main'] = $_main;
$_SESSION['s_id'] = $_id;


//_lg($_SESSION['s_history'], 'HISTORY');

//_lg($_SESSION['s_history_end_pos'], 'HISTORY END POSITION');

$_return['page'] = '';
if ($_main === 'page'){
	if ($_id == 4){
		$g = new _puzzle($_id);
		$_return['page'] .= $g->_fetch_all_puzzles();
	}else if ($_id == 88){
		$_c = new _contact();
		$_return['page'] = $_c->_build_contact_form();
	}else if ($_id == 89){
		$_login = new _login();
		$_return['page'] = $_login->_get_login_page();
	}else if ($_id == 90){
		$_logout = new _login();
		$_logout->_logout();
		$_return['page'] = redirect(__s_app_url__);
	}else{
		$h = new _pages($_id);
		$h->_set_main($_main);
		$_return['page'] .= $h->_build_page();
	}
}else if ($_main === 'topic'){
	$h = new _topic($_id);
	$_topic_id = $h->_get_topic_id();
	$h->_set_main($_main);
	$h->_set_topic_order_array($_SESSION['s_topic_order']);

	$_return['page'] .= $h->_build_topic();

	$_sql = 'select age_levels from _app_topic where id = :id';
	$_d = array('id' => $_topic_id);
	$_f = array('i');
	$_age_level = $_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	//_lg($_age_level, 'AGE LEVELS LP');
	//_lg($_topic_id, 'TOPIC ID');
	if ($_age_level > 3){
		$_SESSION['s_adv_content'] = true;
	}else{
		$_SESSION['s_adv_content'] = false;
	}
	//_lg($_SESSION['s_adv_content'], 'ADV CONTENT LP');
}
$_return['data-id'] = $_id;
$_return['history-pos'] = $_SESSION['s_history_pos'];
$_return['history-end-pos'] = $_SESSION['s_history_end_pos'];
//Rebuild tooltips
$_sql = 'select * from _app_tips where display = 1';
$_div_rows = $_dbh->_fetch_db_rows($_sql);
$_return['tooltips'] = '';
foreach ($_div_rows as $_dr){
	$_title = "<div class='h2 white'>".$_dr['title']."</div>".PHP_EOL;
	$_body = $_title.$_dr['body'];
	if ($_SESSION['s_adv_content']){
		$_body .= $_dr['adv_content'];
	}
	$_return['tooltips'] .= "<div id = 'tt".$_dr['id']."-div' class='hidden'>".$_body."</div>".PHP_EOL;
}


$_return['page-title'] = _set_browser_tab_title();
echo json_encode($_return);
?>