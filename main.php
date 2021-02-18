
<a id = 'nav-skip' href = "#maincol">Skip to main content</a>
<section id = 'main_frm'>
	<header id = 'top' class = 'fixed-header'>
		<div class = ''>
			<img id = 'navburger' width = '50' height = '50' class = 'mr10 ml10 mt5 w40 h40 point ttip' src = '' alt = 'Menu' title = 'Toggle the navigation menu' />
		</div>
		<div id = 'user-info' class = 'pb4 ml50 mr50'>
			<div id = 'now-date' class = 'mr20'></div>
			<div id = 'user-name'></div>
			<div id = 'user-priv' class = 'ml20 mr5'></div>
			<div id = 'logout' class = 'link point ml20 mr5 logout-link' data-id='90' data-main='page'></div>
		</div>
		<img id = 'main-logo' class = 'ttip mr10 ml10' width = '348' height = '30' src = '<?php echo __s_app_url__;?>_images/app_logo.png' alt = '<?php echo __s_app_title__;?>' title = '<?php echo __s_app_title__;?>' />
	</header>
	<div id = 'maincontent'>
		<nav id = 'navbar'><?php
			$nav = new _navmenu();
			$_SESSION['s_topic_order'] = $nav->_get_topic_order();
		?></nav>
		<section id = 'maincol'>
			<?php
			ob_start();
			include_once(__s_app_folder__.'main_menu.php');
			echo ob_get_clean();
			?>
		</section>

	</div>
	<footer id = 'footer' class = 'fixed-footer'>
		<div id = 'idds' class = 'ml5'>
			<div class='link point ml10 mr10' data-id='89' data-main='page'>Login</div>
			<div class='link point mr10' data-id='86' data-main='page'>About me</div>
			<div class='link point mr10' data-id='87' data-main='page'>Mission statement</div>
			<div class='link point' data-id='88' data-main='page'>Contact</div>
		</div>

		<div id = 'icon8'>
			<a rel = 'noreferrer' href = 'https://icons8.com' target = '_blank'>Icons: &copy; Icons8</a>
		</div>
	</footer>
	<div id = 'ajax-loader' class = 'hidden'></div>
		<div id = 'modal-timeout'></div>
<?php
	if (is_logged_in()){?>
		<script src = '<?php echo __s_lib_url__;?>_js/_modal_timeout/modal-timeout.js'></script>
<?php }?>

	<div id  = 'storage' class = 'hidden'></div>
</section>