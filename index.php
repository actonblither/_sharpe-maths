<?php
include('app_config.php');
?>
<!DOCTYPE html>
<html lang = 'en' xmlns:m="http://www.w3.org/1998/Math/MathML">
	<head>
		<title><?php echo __s_app_title__;?></title>
		<meta name = 'Author' content = 'RLBS' />
		<meta name = 'keywords' content = 'Mathematics, Math, Maths, Arithmetic, Algebra, Geometry, equipment, puzzles, educational activities' />
		<meta name = 'description' content = 'Resources for teaching mathematics remotely or in the classroom or for revision.' />
		<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0' />


		<link rel = 'shortcut icon' href = '<?php echo __s_app_url__;?>_images/favicon.ico' />

		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>_style/_app_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_lib.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_navmenu.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_tabs.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_form_elements.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_header_footer.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_list_div.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/tooltipster/css/tooltipster.bundle.min.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>_style/_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_login.css' type = 'text/css' />

		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/ihavecookies/ihavecookies.css' type = 'text/css' />
		<link rel="stylesheet" href="<?php echo __s_applib_url__;?>jquery/jquery_ui.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


		<?php if (is_logged_in()){?>
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/iziToast.min.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/anytime.css' type = 'text/css' />
		<script src = '<?php echo __s_applib_url__;?>classes/ckeditor/ckeditor.js'></script>

		<script src = '<?php echo __s_applib_url__;?>jquery/iziToast.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/dependsOn.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/anytime.js'></script>
		<?php }?>
		<script src = '<?php echo __s_applib_url__;?>jquery/tooltipster/js/tooltipster.bundle.min.js'></script>
		<script src = '<?php echo __s_lib_url__;?>_js/_stdlib.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/ihavecookies/jquery.ihavecookies.js'></script>
		<script src = '<?php echo __s_applib_url__;?>js/fabric.4.3.js'></script>

		<script type = "text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

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

			<?php if (is_logged_in()){?>
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


		</script>
	</head>
	<body>
	<?php
	include_once(__s_app_folder__.'/main.php');?>

	<?php echo _set_browser_tab_title();?>

	<script>


		history.pushState(null, null, location.href);
		console.log(location.href);
    window.onpopstate = function () {
      console.log(history);
        history.go(1);
    };





		$(document).on('keyup','#sfilter', function(){
			var str = $(this).val().toLowerCase();
			$('.filter-field').parent('li').parent('ul').filter(function(){
				$(this).toggle($(this).text().toLowerCase().indexOf(str) > -1)
			});
		});

		$(document).on('click touch', 'li.expand', function(evt){
			evt.stopImmediatePropagation();
			if ($(this).hasClass('link') == false){
				var id = $(this).attr('data-id');
				$('ul#uxp'+id).toggleClass('hidden');
				if (readCookie('uxp'+id) == 'c'){
					createCookie('uxp'+id, 'o', 365);
				}else{
					createCookie('uxp'+id, 'c', 365);
				}
			}
		});

		$(document).on('click touch', 'li.link', function(evt){
			_load_page(evt, this);
			$('ul.nav-menu-side li.link').removeClass('nms');
			$(this).addClass('nms');
		});

		$(document).on('click touch', 'div.link, span.link', function(evt){
			_load_page(evt, this);
		});

		$(document).on('click touch', 'img.nav_arrow', function(evt){
			_load_page(evt, this);
			var id = $(this).attr('data-id');
			$('ul.nav-menu-side li.link').removeClass('nms');
			$('li#navli'+id).addClass('nms');
		});

		$(document).on('click touch', 'span.topic-link, span.page-link', function(evt){
			_load_page(evt, this);
			var id = $(this).attr('data-id');
			$('ul.nav-menu-side li.link').removeClass('nms');
			$('li#navli'+id).addClass('nms');
		});

		$(document).on('click touch', '#b_btn', function(e) {
			if ($(this).hasClass('inactive') == false){
				_load_page(e, this, 'back');
			}
		});

		$(document).on('click touch', '#f_btn', function(e) {
			if ($(this).hasClass('inactive') == false){
				_load_page(e, this, 'forward');
			}
		});




		var _load_page = function(evt, f, link_type = 'link'){
			evt.stopImmediatePropagation();
			if (link_type == 'link'){
				var id = $(f).attr('data-id');
				var main = $(f).attr('data-main');
			}else{
				var id = 0;
				var main = '';
			}
			var fd = new FormData();
			fd.append('main', main);
			fd.append('id', id);
			fd.append('link_type', link_type);
			fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
			$.ajax({
				type		: 'POST',
				cache			: false,
				dataType : 'json',
				processData	: false,
				contentType	: false,
				url		: '<?php echo __s_lib_url__;?>_ajax/_load_page.php',
				data		: fd,
				beforeSend: function() {
					$('#ajax-loader').removeClass('hidden');
				},
				success : function(data) {
					console.log(data['page']);
					$('#maincol').html(data['page']);
					$('#glosstip_content_container').html(data['tooltips']);
					$('title').html(data['page-title']);

					var id = data['data-id'];
					var h_pos = data['history-pos'];
					var h_end_pos = data['history-end-pos'];
					if (h_pos == 0){
						$('#b_btn').addClass('inactive');
					}else{
						$('#b_btn').removeClass('inactive');
					}
					if (h_pos == h_end_pos){
						$('#f_btn').addClass('inactive');
					}else{
						$('#f_btn').removeClass('inactive');
					}
					//$('ul.nav-menu-side li.link').removeClass('nms');
					//$('li#navli'+id).addClass('nms');

					MathJax.typeset();
				},
				complete: function(){
					$('#ajax-loader').addClass('hidden');
				},
				error: function(){
					$('#ajax-loader').addClass('hidden');
				}
			});
		}

		$(document).on('click touch', '#navburger', function(){
			var tog;
			var burger_src;
			if (readCookie('navbar') == 'off'){
				burger_src = './_stdlib/_images/_icons/close50.png';
				tog = 'on';
			}else{
				burger_src = '_stdlib/_images/_icons/menu50.png';
				tog = 'off';
			}
			this.src = burger_src;
			createCookie('navbar', tog, 365);
			if (tog === 'on'){
				$('#navbar').removeClass('hidden');
			}else{
				$('#navbar').addClass('hidden');
			}
		});


		$(document).on('click touch', '#navswitch', function(){
			nav_switch();
		});

		var nav_switch = function(){
			var dir = $('#fixed-header').css('flex-direction');
			var pos = readCookie('nav-position');
			var new_dir = (dir === 'row') ? 'row-reverse' : 'row';
			var new_pos = (pos === 'r') ? 'l' : 'r';
			$('#maincontent').css('flex-direction', new_dir);
			$('#fixed-header').css('flex-direction', new_dir);
			$('#navbar').css('margin', '0');
			var side_margin = (new_pos === 'r') ? 'margin-left' : 'margin-right';
			$('#navbar').css(side_margin, '2px');

			createCookie('nav-position', new_pos, 365);
		}

		$(document).on('click touch', '#b_btn', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();

		});

		$(document).on('click touch', '#f_btn', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();

		});

		$(document).on('click touch', '.reveal', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var id = $(this).attr('data-id');
			var pre = $(this).attr('data-text-div');
			var text_div = pre + id;
			$('div#'+ text_div).toggleClass('hidden');
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

	$(document).on('click touch', '.del_s_ex_q', function(e){
		if (_sure('Are you sure you want to delete this question?')){
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('id').substring(3);
		var fd = new FormData();
		fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
		fd.append('main_db_tbl', '_app_topic_ex_q');
		fd.append('main_db_tbl_field', 'id');
		fd.append('main_db_tbl_field_value', id);
		$.ajax({
			type: 'POST',
			async : true,
			cache : false,
			processData	: false,
			contentType	: false,
			url: '<?php echo __s_lib_url__;?>_ajax/_delete_records.php',
			data: fd,
			dataType: 'json',
			success: function (data) {
				$('li#sub' + id).remove();
			}
		});
		}
	});


	$(document).on('click touch', '.add_new', function(e){
		var item_name = $(this).attr('data-item-name');
		if (_sure('Are you sure you want to add a new '+ item_name +'?')){
			e.preventDefault();
			e.stopImmediatePropagation();
			var topic_id = $(this).attr('data-topic-id');
			var db_tbl = $(this).attr('data-db-tbl');
			var parent_list_name = $(this).attr('data-parent-list-name');
			var item_class_name = $(this).attr('data-item-class-name');
			var admin_template = $(this).attr('data-admin-template');
			var field_prefix = $(this).attr('data-field-prefix');
			var fd = new FormData();
			fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
			fd.append('db_tbl', db_tbl);
			fd.append('item_name', item_name);
			fd.append('topic_id', topic_id);
			fd.append('item_class_name', item_class_name);
			fd.append('admin_template', admin_template);
			fd.append('field_prefix', field_prefix);
			$.ajax({
				type: 'POST',
				async : true,
				cache : false,
				processData	: false,
				contentType	: false,
				url: '<?php echo __s_lib_url__;?>_ajax/_add_new_topic_item.php',
				data: fd,
				dataType: 'json',
				success: function (data) {
					$('ul#' + parent_list_name).append(data);
				}
			});
		}
	});

	//Listener to save any field with class = 'field'
	$(document).on('keyup', '.field', function(e){
		//console.log(e.which);
		if (e.ctrlKey && e.keyCode == 83 || e.keyCode == 112 || e.keyCode == 113 || e.keyCode == 114 || e.keyCode == 27) {
			_save_field(this, e);
		}
	});

	$(document).on('blur', '.field', function(e){
		_save_field(this, e);
	});

	$(document).on('change', '.chk-field', function(e){
		var chkd = $(this).is(':checked');
		_save_field(this, e, chkd, false);
	});

	$(document).on('change', '.sel-field', function(e){
		_save_field(this, e, false, false);
	});

	$(document).on('change', '.sel-link-field', function(e){
		_save_field(this, e, false, true);
	});

	$(document).on('click touch', '.page-save', function(e){
		_save_field(this, e, false, false);
	});

	var _save_CKE_field = function(cke){
		var fd = new FormData();
		fd.append('id', cke['id']);
		fd.append('field', cke['field']);
		fd.append('value', cke['val']);
		fd.append('db_tbl', cke['tbl']);
		fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
		$.ajax({
			type: 'POST',
			cache: false,
			dataType: 'json',
			processData: false,
			contentType: false,
			url: '<?php echo __s_lib_url__;?>_ajax/_save_field.php',
				data: fd,
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


	var _save_field = function(f, e, chkd = false, link = false){
		e.preventDefault();
		e.stopImmediatePropagation();
		for (var i in CKEDITOR.instances) {
			CKEDITOR.instances[i].updateElement();
		}
		var el_id = $(f).attr('id');
		var data_id = $(f).attr('data-id');
		var data_field = $(f).attr('data-field');
		var db_tbl = $(f).attr('data-db-tbl');
		var data_field1 = $(f).attr('data-field1');
		var data_field2 = $(f).attr('data-field2');
		var data_value2 = data_id;
		var data_value1;
		var link_self_ref = $(f).attr('data-link-self-ref');
		var _el_type = $(f).attr('data-el-type');

		if (link == false){
			var data_field_id = data_field + "_" + data_id;
			var data_value = $('#'+data_field_id).val();
			if (_el_type === 'checkbox'){
				if (chkd){
					data_value = 1;
				}else{
					data_value = 0;
				}
				//console.log(data_value);
			}
		}else{
			data_value1 = $('#'+ el_id).val();
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
		fd.append('data-link-self-ref', link_self_ref);
		fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
		$.ajax({
			type: 'POST',
			cache: false,
			dataType: 'json',
			processData: false,
			contentType: false,
			url: '<?php echo __s_lib_url__;?>_ajax/_save_field.php',
				data: fd,
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

		$(document).on('click touch', '.open-list', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();

			var id = $(this).attr('id');
			var oid = $(this).attr('data-list-id');
			var img_cl_id = $(this).attr('data-img-cl');
			var img_op_id = $(this).attr('data-img-op');

			if ($('ul#' + oid).hasClass('hidden')){
				$('ul#' + oid).removeClass('hidden');
				$('img#' + img_cl_id).removeClass('hidden');
				$('img#' + img_op_id).addClass('hidden');
				var state = 'open';
			}else{
				$('ul#' + oid).addClass('hidden');
				$('img#' + img_op_id).removeClass('hidden');
				$('img#' + img_cl_id).addClass('hidden');
				var state = 'closed';
			}
			createCookie(oid, state, 365);
		});

		$(document).on('click touch', '.open_eg', function(e){
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



		$(document).on('click touch', '.open_ex', function(e){
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

		$(document).on('click touch', '.open_act', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var id = $(this).attr('id').substring(2);
			if ($('ul#acts'+id).hasClass('hidden')){
				$('ul#acts'+id).removeClass('hidden');
				$('img#jc'+id).removeClass('hidden');
				$('img#jo'+id).addClass('hidden');
			}else{
				$('ul#acts'+id).addClass('hidden');
				$('img#jo'+id).removeClass('hidden');
				$('img#jc'+id).addClass('hidden');
			}
		});

		$(document).on('click touch', '.ans', function(e){
			var id = $(this).attr('id');
			var oid = $(this).attr('data-oid')

			if (e.ctrlKey == false){
				$('#' + id ).toggleClass('hidden');
				$('#' + oid ).toggleClass('hidden');
			}
		});


		$(document).on('click touch', '.solution', function(evt){
			var id = $(this).attr('id');
			if (evt.ctrlKey == false){
				//console.log(id);
				$('#' + id + ' .back-s').toggleClass('hidden');
				$('#' + id + ' .front-s').toggleClass('hidden');
			}
		});

		$(document).on('click touch', '.method', function(evt){
			var id = $(this).attr('id');
			if (evt.ctrlKey == false){
				//console.log(id);
				$('#' + id + ' .back-m').toggleClass('hidden');
				$('#' + id + ' .front-m').toggleClass('hidden');
			}
		});

	<?php if (is_logged_in()){?>
		$('.sortable-list').sortable({
			items: 'li.rc',
			update: function(event, ui) {
				var new_list = $(this).sortable('toArray').toString();
				var db_tbl = $(this).attr('data-db-tbl');
				var sort_list_prefix = $(this).attr('data-sort-list-prefix');
				if (db_tbl === undefined){
					var db_tbl = $(this.firstChild.nextElementSibling).attr('data-db-tbl');
					var sort_list_prefix = $(this.firstChild.nextElementSibling).attr('data-sort-list-prefix');
				}
				var fd = new FormData();
				fd.set('nlist', new_list);
				fd.set('gen_table', db_tbl);
				fd.set('sort_list_prefix', sort_list_prefix);
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

		$(document).on('keydown', "textarea", function(e) {
			if(e.keyCode === 9) { // tab was pressed
				// get caret position/selection
				var start = this.selectionStart;
				var end = this.selectionEnd;
				var $this = $(this);

				// set textarea value to: text before caret + tab + text after caret
				$this.val($this.val().substring(0, start) + "\t" + $this.val().substring(end));

				// put caret at right position again
				this.selectionStart = this.selectionEnd = start + 1;

				// prevent the focus lose
				return false;
			}
		});
	<?php }?>

			$(function() {
				$(document).tooltip({
					items: ".glosstip",
					show: { duration: 0 },
					hide: { effect: "fade", duration: 100 },
					position: { my: "left top", at: "left bottom" },
					content: function() {
						return $('#'+$( this ).attr('data-ttcontent')+'-div').html();
					},
				});
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

		</script>
		<div id = 'glosstip_content_container' class='hidden'>
			<?php
			$_dbh = new _db();
			$_sql = 'select * from _app_tips where display = 1';
			$_div_rows = $_dbh->_fetch_db_rows($_sql);

			foreach ($_div_rows as $_dr){
				$_title = "<div class='h2 white'>".$_dr['title']."</div>".PHP_EOL;
				$_body = $_title.$_dr['body'];
				if ($_SESSION['s_adv_content']){
					$_body .= $_dr['adv_content'];
				}
				echo "<div id = 'tt".$_dr['id']."-div' class='hidden'>".$_body."</div>".PHP_EOL;
			}
		?>
		</div>
	</body>
</html>
