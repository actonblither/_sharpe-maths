<?php
$_main = rvs($_REQUEST['main'], $_SESSION['s_main']);
$_id = rvz($_REQUEST['id'], $_SESSION['s_id']);

if ($_SESSION['s_id'] === 89 || $_SESSION['s_id'] === 90){
	$_SESSION['s_id'] = 1;
	$_id = 1;
}

if (empty($_main)){$_main = 'page';}
if (empty($_id)){$_id = 1;}

$_SESSION['s_main'] = $_main;
$_SESSION['s_id'] = $_id;

//_cl($_SESSION['s_main']);
//_cl($_SESSION['s_id']);

if (is_logged_in()){
	$_setup = new _setup();?>
	<script>
		var user_name = '<?php echo ($_setup->_fetch_current_admin_user_name());?>';
		var user_priv = '<?php echo ($_setup->_fetch_access_name());?>';
		$('#user-name').html("<span class = 'b'>User:<?php if (defined('__s_app_au_name__')){ echo __s_app_au_name__;}?><\/span> " + user_name);
		$('#user-priv').html("<span class = 'b'>Priv:<\/span> " + user_priv);
		$('#logout').html("Logout");
		$('li#navli<?php echo $_SESSION['s_id'];?>').addClass('nms');
	</script>
<?php }else{?>
	<script>$('li#navli<?php echo $_SESSION['s_id'];?>').addClass('nms');</script>
<?php }

switch ($_main){

	case 'contact':
		$_c = new _contact();
		echo $_c->_build_contact_form();
		break;

	case 'page':
		if ($_id === 4){
			//$g = new _puzzle();
			//echo $g->_fetch_all_puzzles();
		}else if ($_id === 5){
			$h = new _glossary();
			echo $h->_build_glossary();
		}else if ($_id === 88){
			$_c = new _contact();
			echo $_c->_build_contact_form();
		}else{
			$h = new _pages($_id);
			echo $h->_build_page();
		}
		break;

	case 'topic':
		$h = new _topic($_id);
		echo $h->_build_topic();
		break;

}

?>
