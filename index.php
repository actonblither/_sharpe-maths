<?php
include('app_config.php');
?>
<!DOCTYPE html>
<html lang = 'en'>
	<head>
		<title><?php echo __s_app_title__;?></title>
		<meta name = 'Author' content = 'RLBS' />
		<link rel = 'shortcut icon' href = '<?php echo __s_app_url__;?>/_images/favicon.ico' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>/jquery/iziToast.min.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>/jquery/anytime.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>/jquery/tooltipster/css/tooltipster.bundle.css' type = 'text/css' />

		<link rel = 'stylesheet' href  = '<?php echo __s_app_url__;?>/_style/_app_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href  = '<?php echo __s_lib_url__;?>/_style/_style_init.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_ul.css' type  = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_login.css' type  = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_nav.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_navmenu.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_topic.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>/_style/_style.css' type = 'text/css' />

		<script src = '<?php echo __s_applib_url__;?>jquery/jquery.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/jquery-ui.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/iziToast.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/tooltipster/js/tooltipster.bundle.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/dependsOn.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/anytime.js'></script>
		<script src = '<?php echo __s_applib_url__;?>js/_stdlib.js'></script>
		<script src = '<?php echo __s_applib_url__;?>classes/ckeditor/ckeditor.js'></script>

<script>
MathJax = {
	chtml: {
		displayAlign: 'left'
	},
	tex: {
		inlineMath: [['$', '$'], ['\\(', '\\)']],
		displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
		processEscapes: false
	},
	svg: {
		fontCache: 'global'
	}
};
</script>
<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

		<script>
			/* 	ti = title
			me = message
			st = status
			script = the script with the error_get_last
			context = id of target
			response = last resort error
			*/
			function add_note(ti, me, st, script = '', context = '', response = ''){
				if (st == 'error' && script != ''){
					var extra = '<a style = \'color:red;font-weight:bold;\' href = \'mailto::rs@skarpur.co.uk?subject=Error in ' + script + '&body = ' + btoa(response) + '\'>Please click this link to send the error to the website administrator. Just click the link and send the email.<\/a>';
					me = me + '<br /><br />' + extra;
				}
				iziToast.settings({position: 'topRight', timeout: 3000, theme: 'dark', color: 'appcol', closeOnEscape: true, close: true});
				if (st == 'success'){iziToast.success({title: ti, message: me});}
				if (st == 'error'){iziToast.error({title: ti, message: me});}
				if (st == 'info'){iziToast.info({title: ti, message: me});}
			}

			$(document).ready(function(){
			<?php
			if (rvz($GLOBALS['s_show_tooltips']) == 1) {?>
					$(document).on('mouseenter', '.ttip:not(.tooltipstered)', function(){
						$(this).tooltipster({
							theme: 'tooltipster-app',
							animation: 'fade',
							animationDuration: 900,
							maxWidth: 500,
							contentAsHTML: true,
							trigger: 'hover'
						}).tooltipster('show');
					});
			<?php }?>
			});
		</script>
	</head>
	<body class = 'pb20'>

	<?php
	include_once('./main.php');
	echo _set_browser_tab_title();?>

	</body>
</html>