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
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>/_style/_style_login.css' type = 'text/css' />

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
		<script src = '<?php echo __s_applib_url__;?>js/fabric.4.3.js'></script>
		<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

<script>
	MathJax = {

		loader: {load: ['[tex]/cancel']},
		tex: {
			inlineMath: [['$', '$'], ['\\(', '\\)']],
			packages: {'[+]': ['cancel']}
		},
		svg: {
			fontCache: 'global',
			displayAlign: 'left',
			scale: 1
		}
	};

	function _sure(message){
		if (confirm(message)){
			return true;
		}else{
			return false;
		}
	}

	$(document).ready(function(e){

		$(document).on('keyup','#sfilter', function(){
			var str = $(this).val().toLowerCase();
			$('.filter-field').parent('li').parent('ul').filter(function(){
				$(this).toggle($(this).text().toLowerCase().indexOf(str) > -1)
			});
		});

		$(document).on('click', 'li.expand', function(evt){
			evt.stopImmediatePropagation();
			console.log($(this));
			if ($(this).hasClass('link') == false){
				var id = $(this).attr('id');
				$('ul#uxp'+id).slideToggle().toggleClass('hidden');
			}
		});

		$(document).on('click', '#menu', function(){
			if ($('#navbar').hasClass('hidden')){
				$('#navbar').removeClass('hidden');
				createCookie('navbar', 1, 365);
			}else{
				$('#navbar').addClass('hidden');
				createCookie('navbar', 0, 365);
			}
		});

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


	var now = new Date();
	var time = now.getTime();
	var expireTime = time + 60 * 60 * 24 * 365;
	var options = {
		title: 'Accept Cookies?',
		message: 'Cookies are used on this site, to improve usability. The site will operate with reduced functionality without them. However, no tracking or marketing cookies are employed at all.',
		delay: 600,

		expires: expireTime,
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
		_save_field(this, e);
	});

	$(document).on('change', '.sel-link-field', function(e){
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
		var db_tbl = $(f).attr('data-db-tbl');
		var data_field1 = $(f).attr('data-field1');
		var data_field2 = $(f).attr('data-field2');
		var data_value2 = $(f).attr('data-value2');
		var _el_type = $(f).attr('data-el-type');


		if (link == false){
			var data_field_id = data_field + "_" + data_id;
			var data_value = $('#'+data_field_id).val();
			if (_el_type === 'checkbox'){
				data_value = $('#'+data_field_id).is(':checked');
				if (data_value == true){
					data_value = 1;
				}else{
					data_value = 0;
				}
			}
		}else{

			var data_value1 = [];
			$('#link_id_' + data_value2 + ' option').each(function() {
				if (this.selected) {
					data_value1.push(this.value);
				}
			});
		}
		var fd = new FormData();
		fd.append('link', link);
		fd.append('id', data_id);
		fd.append('field', data_field);
		fd.append('value', data_value);

		fd.append('data_field1', data_field1);
		fd.append('data_field2', data_field2);
		fd.append('data_value1', data_value1);
		fd.append('data_value2', data_value2);
		fd.append('db_tbl', db_tbl);
		fd.append('data-el-type', _el_type);
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
	<?php }?>


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

	$(document).on('click', '.open_exp', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('id').substring(2);
		if ($('ul#exps'+id).hasClass('hidden')){
			$('ul#exps'+id).removeClass('hidden');
			$('img#jc'+id).removeClass('hidden');
			$('img#jo'+id).addClass('hidden');
		}else{
			$('ul#exps'+id).addClass('hidden');
			$('img#jo'+id).removeClass('hidden');
			$('img#jc'+id).addClass('hidden');
		}
	});

	$(document).on('click', '.eye', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		console.log($(this).attr('id'));
		var id = $(this).attr('id').substring(3);
		if ($('#eye'+id).hasClass('hidden')){
			$('#eye'+id).removeClass('hidden');
			$('#ans'+id).addClass('hidden');
		}else{
			$('#eye'+id).addClass('hidden');
			$('#ans'+id).removeClass('hidden');
		}
	});

<?php if (is_logged_in()){?>
	$('.sortable-list').sortable({
		items: 'li.ex, li.eg, li.exp',
		update: function(event, ui) {
			var new_list = $(this).sortable('toArray').toString();
			var db_tbl = $(this.firstChild.nextSibling).attr('data-db-tbl');
			var fd = new FormData();
			fd.set('nlist', new_list);
			fd.set('gen_table', db_tbl);
			fd.set('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
			$.ajax({
				type: 'POST',
				async : true,
				cache : false,
				processData	: false,
				contentType	: false,
				url: '<?php echo __s_lib_url__;?>_ajax/_record_order_update.php',
				data: fd,
				dataType: 'json',
				success: function (data) {
					var title = data['title'];
					var message = data['message'];
					var status = data['status'];
					add_note(title, message, status);
				}
			});
		}
	});
<?php }?>

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
function _navbar_cookie(){
	if (readCookie('navbar') == 0 || readCookie('navbar') == null){
		$('#navbar').addClass('hidden');
	}else{
		$('#navbar').removeClass('hidden');
	}
}
</script>





	</head>
	<body class = 'pb20' onload='_navbar_cookie();'>

	<?php
	include_once('./main.php');
	echo _set_browser_tab_title();?>

	</body>
</html>
