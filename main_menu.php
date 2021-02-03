<?php
if (is_logged_in()){
	$_setup = new _setup();?>
	<script>
		var user_name = '<?php echo ($_setup->_fetch_current_admin_user_name());?>';
		var user_priv = '<?php echo ($_setup->_fetch_access_name());?>';
		$('#user-name').html("<span class = 'b'>User:<?php if (defined('__s_app_au_name__')){ echo __s_app_au_name__;}?><\/span> " + user_name);
		$('#user-priv').html("<span class = 'b'>Priv:<\/span> " + user_priv);
		$('#logout').html("<a class = 'b' href = 'index.php?main=logout'>Logout</a>");
	</script>
<?php }?>


<?php
$_main = rvs($_REQUEST['main'], 'page');
$_id = rvz($_REQUEST['id'], 1);

$_SESSION['s_main'] = $_main;
$GLOBALS['s_main'] = $_main;

switch ($_main){
	case 'login':
		$_login = new _login();
		$_login->_get_login_page();
		break;

	case 'logout':
		$_logout = new _login();
		$_logout->_logout();
		break;

	case 'contact':
		$_c = new _contact();
		break;

	case 'page':
		$h = new _pages(1);
		echo $h->_build_page();
		break;

	case 'topic':
		if ($_id == 5){
			$h = new _glossary();
		}else{
			$h = new _topic();
		}
		break;

}

?>
