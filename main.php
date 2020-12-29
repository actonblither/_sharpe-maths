<?php
if ($_COOKIE['navbar'] == 0){
	$_hidden = "class = 'hidden'";
}else{
	$_hidden = "";
}
?>
<a id = 'nav-skip' href = "#maincol">Skip to main content</a>
<section id = 'main_frm'>
	<header id = 'top' class = 'fixed-header'>
		<div class = 'w190'>
			<div class = 'w40'><img id = 'menu' class = 'ml5 mb5 point ttip' src = '<?php echo __s_icon_url__;?>32/menu32.png' alt = 'Menu' title = 'Toggle the navigation menu' /></div>
		</div>
		<div class = 'w300'><img id = 'main-logo' class = 'ttip' src = '_images/app_logo.png' alt = '<?php echo __s_app_title__;?>' title = '<?php echo __s_app_title__;?>' /></div>
		<div id = 'user-info'>
			<div id = 'now-date' class = 'mr20'></div>
			<div id = 'user-name'></div>
			<div id = 'user-priv' class = 'ml20 mr5'></div>
			<div id = 'logout' class = 'ml20 mr5'></div>
		</div>
	</header>
	<div id = 'maincontent'>
		<nav id = 'navbar' <?php echo $_hidden;?>><?php $nav = new _navmenu();?></nav>
		<section id = 'maincol'>
			<?php
			ob_start();
			include_once(__s_app_folder__.'/main_menu.php');
			echo ob_get_clean();
			?>
		</section>
	</div>
	<header id = 'footer' class = 'fixed-footer'>
		<div id = 'idds' class = 'ml5'>
			<a href='index.php?main=page&amp;id=12'>About me</a>
			<a href = 'index.php?main=contact' class='ml10'>Contact</a>
		</div>
		<div id = 'icon8'>
			<a href = 'https://icons8.com' target = '_blank'>Icons: &copy; Icons8</a>
		</div>
	</header>
	<div id = 'ajax-loader' class = 'hidden'></div>
		<div id = 'modal-timeout'></div>
<?php
	if (is_logged_in()){?>
		<script src = '<?php echo __s_lib_url__;?>/_js/_modal_timeout/modal-timeout.js'></script>
<?php }?>

	<div id  = 'storage' class = 'hidden'></div>
</section>