<?php
$_base_folder = base64_decode($_POST['app_folder']);
$_base_folder = filter_var($_base_folder, FILTER_SANITIZE_STRING);
include($_base_folder.'/app_config.php');
//_cl($_POST);

$_addr = rvs($_POST['addr']);
$_message = rvs($_POST['message']);
$_dbh = new _db();

if (isRussian($_message)){$_message = '';}
if (is_genuine_email($_addr)){$_fake = 0;}else{$_fake = 1;}
if (!empty($_addr) && !empty($_message)){
	$_t = '__sys_contact_messages';
	$_d = array('email' => $_addr, 'message' => $_message, 'fake' => $_fake);
	$_f = array('s', 's', 'i');
	$_insert_id = $_dbh->_insert($_t, $_d, $_f);
}else{
	$_insert_id = 1;
}


if ($_insert_id){
	$_return['status'] = 'success';
	$_return['title'] = 'Post success';
	$_return['message'] = 'The message has been successfully posted.';
}else{
	$_return['status'] = 'failure';
	$_return['title'] = 'Post failure';
	$_return ['message'] = 'There was a problem posting your message. Please try again.';
}
echo json_encode($_return);


function isRussian($text) {
	return preg_match('/[А-Яа-яЁё]/u', $text);
}
?>