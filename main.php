<a id = 'nav-skip' href = "#maincol">Skip to main content</a>
<header id = 'fixed-header'>
	<?php
		$_h = new _header_body();
		echo $_h->_build_header();
	?>
</header>
<section id = 'maincontent'>
	<?php echo $_h->_build_body();?>
</section>
<footer id = 'fixed-footer'>
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
<?php if (is_logged_in()){?>
	<script src = '<?php echo __s_lib_url__;?>_js/_modal_timeout/modal-timeout.js'></script>
<?php }?>