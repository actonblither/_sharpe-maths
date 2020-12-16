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

		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>/_style/_app_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_complete.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>/_style/_style.css' type = 'text/css' />

		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>/jquery/ihavecookies/ihavecookies.css' type = 'text/css' />

		<script src = '<?php echo __s_applib_url__;?>jquery/jquery.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/jquery-ui.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/iziToast.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/tooltipster/js/tooltipster.bundle.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/dependsOn.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/anytime.js'></script>
		<script src = '<?php echo __s_applib_url__;?>js/_stdlib.js'></script>
		<script src = '<?php echo __s_applib_url__;?>classes/ckeditor/ckeditor.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/ihavecookies/jquery.ihavecookies.js'></script>

<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

<script>
	MathJax = {
		tex: {
			inlineMath: [['$', '$'], ['\\(', '\\)']]
		},
		svg: {
			fontCache: 'global',
			displayAlign: 'left',
			scale: 1.0
		}
	};

	$(document).ready(function(e){

		$(document).on('click', '.reveal', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var id = $(this).attr('data-id');
			var pre = $(this).attr('data-text_div');
			var text_div = pre + id;

			if ($('#'+ text_div).hasClass('hidden')){
				$('#hc'+id).addClass('hidden');
				$('#sc'+id).addClass('hidden');
				$('#ec'+id).addClass('hidden');
				$('#' + text_div).removeClass('hidden');
			}else{
				$('#' + text_div).addClass('hidden');
			}
		});

		$(document).on('click', '#send', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var addr = $('#address').val();
			var message = $('#message').val();
			var fd = new FormData();
			fd.append('addr', addr);
			fd.append('message', message);
			fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
				$.ajax({
					type		: 'POST',
					cache			: false,
					dataType : 'json',
					processData	: false,
					contentType	: false,
					url		: '_stdlib/_ajax/_contact_send.php',
					data		: fd,
					beforeSend: function() {
						$('#ajax-loader').removeClass('hidden');
					},
					success : function(data) {
						var title = data['title'];
						var message = data['message'];
						var status = data['status'];
						add_note(title, message, status);
					},
					complete: function(){
						$('#ajax-loader').addClass('hidden');
					},
					error: function(){
						$('#ajax-loader').addClass('hidden');
					}
				});
		});



	var options = {
		title: 'Accept Cookies?',
		message: 'Cookies are used on this site, to improve usability. The site will operate with reduced functionality without them. However, no tracking or marketing cookies are employed at all.',
		delay: 600,
		expires: 1,
		link: '',
		onAccept: function(){
			var myPreferences = $.fn.ihavecookies.cookie();
		},
		uncheckBoxes: true,
		acceptBtnLabel: 'Accept Cookies',
		moreInfoLabel: '',
		advancedBtnLabel: '',
		cookieTypes: [{
			type: 'Site Preferences',
			value: 'preferences',
			description: 'These are cookies that are related to your site preferences, e.g. remembering your username, site colours, etc.'
		}],
		fixedCookieTypeLabel: 'Essential',
		fixedCookieTypeDesc: 'These are essential for the website to work correctly.'
	}

	$('body').ihavecookies(options);

<?php if (is_logged_in()){?>

	$(document).on('blur', '.field', function(e){
		_save_field(this, e);
	});

	$(document).on('change', '.sel-field', function(e){
		_save_field(this, e, true);
	});

	$(document).on('click', '.page-save', function(e){
		_save_field(this, e);
	});

	var _save_field = function(f, e, link = false){
		e.preventDefault();
		e.stopImmediatePropagation();
		//Force CKeditor to update the textarea field before collecting the FormData
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}

		var data_id = $(f).attr('data-id');
		var data_field = $(f).attr('data-field');
		var db_tbl = $(f).attr('data-db_tbl');

		if (link == false){
			var data_field_id = data_field + "_" + data_id;
			var data_value = $('#'+data_field_id).val();
		}else{
			var data_value = [];
			$('#link_id_'+data_id + ' option').each(function(i) {
				if (this.selected == true) {
					data_value.push(this.value);
				}
			});
		}
		var fd = new FormData();
		fd.append('link', link);
		fd.append('id', data_id);
		fd.append('field', data_field);
		fd.append('value', data_value);
		fd.append('db_tbl', db_tbl);
		fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
			$.ajax({
				type		: 'POST',
				cache			: false,
				dataType : 'json',
				processData	: false,
				contentType	: false,
				url		: '_ajax/_save_field.php',
				data		: fd,
				beforeSend: function() {
					$('#ajax-loader').removeClass('hidden');
				},
				success : function(data) {
					var title = data['title'];
					var message = data['message'];
					var status = data['status'];
					add_note(title, message, status);
				},
				complete: function(){
					$('#ajax-loader').addClass('hidden');
				},
				error: function(){
					$('#ajax-loader').addClass('hidden');
				}
			});
		}



		/* 	ti = title
		me = message
		st = status
		script = the script with the error_get_last
		context = id of target
		response = last resort error
		*/
		function add_note(ti, me, st, script = '', context = '', response = ''){
			if (st == 'error' && script != ''){
				var extra = '<a style = \'color:red;font-weight:bold;\' href = \'mailto::rs@idds.uk?subject=Error in ' + script + '&body = ' + btoa(response) + '\'>Please click this link to send the error to the website administrator. Just click the link and send the email.<\/a>';
				me = me + '<br /><br />' + extra;
			}
			iziToast.settings({position: 'topRight', timeout: 3000, theme: 'dark', color: 'appcol', closeOnEscape: true, close: true});
			if (st == 'success'){iziToast.success({title: ti, message: me});}
			if (st == 'error'){iziToast.error({title: ti, message: me});}
			if (st == 'failure'){iziToast.info({title: ti, message: me});}
			if (st == 'info'){iziToast.info({title: ti, message: me});}
		}
<?php }?>


	$(document).on('click', '.open_eg', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('id').substring(2);
		if ($('ul#egqs'+id).hasClass('hidden')){
			$('ul#egqs'+id).removeClass('hidden');
			$('img#ic'+id).removeClass('hidden');
			$('img#io'+id).addClass('hidden');
		}else{
			$('ul#egqs'+id).addClass('hidden');
			$('img#io'+id).removeClass('hidden');
			$('img#ic'+id).addClass('hidden');
		}
	});

	$(document).on('click', '.open_ex', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('id').substring(2);
		if ($('ul#exqs'+id).hasClass('hidden')){
			$('ul#exqs'+id).removeClass('hidden');
			$('img#ac'+id).removeClass('hidden');
			$('img#ao'+id).addClass('hidden');
		}else{
			$('ul#exqs'+id).addClass('hidden');
			$('img#ao'+id).removeClass('hidden');
			$('img#ac'+id).addClass('hidden');
		}
	});

	$(document).on('click', '.answer', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('id').substring(7);
		if ($('#visible'+id).hasClass('eye')){
			$('#visible'+id).removeClass('eye');
			$('#visible'+id).addClass('ans');
			$('#visible'+id).html($('#ans_store'+id).html());
		}else{
			$('#visible'+id).addClass('eye');
			$('#visible'+id).removeClass('ans');
			$('#visible'+id).html($('#eye_store'+id).html());
		}
	});

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
});
</script>





	</head>
	<body class = 'pb20'>

	<?php
	include_once('./main.php');
	echo _set_browser_tab_title();?>

	</body>
</html>
