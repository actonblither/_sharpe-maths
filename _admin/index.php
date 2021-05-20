<?php include '../app_config.php';?>

<!DOCTYPE html>
<html lang = 'en' xmlns:m="http://www.w3.org/1998/Math/MathML">
	<head>
	<link rel = 'shortcut icon' href = '<?php echo __s_app_url__;?>_images/favicon.ico' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/tooltipster/css/tooltipster.bundle.min.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>_style/_app_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_lib.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_navmenu.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_tabs.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_form_elements.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_header_footer.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_list_div.css' type = 'text/css' />

		<link rel = 'stylesheet' href = '<?php echo __s_app_url__;?>_style/_style.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_lib_url__;?>_style/_style_login.css' type = 'text/css' />


		<script src='<?php echo __s_applib_url__;?>jquery/jquery-3.5.1.js'></script>

		<?php if (is_logged_in()){?>
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/iziToast.min.css' type = 'text/css' />
		<link rel = 'stylesheet' href = '<?php echo __s_applib_url__;?>jquery/anytime.css' type = 'text/css' />
		<script src = '<?php echo __s_applib_url__;?>classes/ckeditor/ckeditor.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/jquery-ui.min.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/anytime.js'></script>
		<script src = '<?php echo __s_applib_url__;?>jquery/iziToast.min.js'></script>
		<?php }?>

		<script src = '<?php echo __s_lib_url__;?>_js/_stdlib.js'></script>
		<script src = '<?php echo __s_applib_url__;?>js/fabric.4.3.js'></script>

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



		</script>
	</head>

	<body>
<?php
$_m = rvs($_REQUEST['main']);
if ($_m == 'nav'){
	include ('_nav.php');
}else if ($_m == 'topic'){
	include ('_topic.php');
}else if ($_m == 'page'){
	include ('_page.php');
}

?>



		<script>
			$(document).on('click', '.exp', function(evt){
				evt.stopImmediatePropagation();
				if ($(this).hasClass('link') == false){
					var id = $(this).attr('data-id');
					$('ul#uxa'+id).toggleClass('hidden');
					if (readCookie('uxa'+id) == 'c'){
						createCookie('uxa'+id, 'o', 365);
					}else{
						createCookie('uxa'+id, 'c', 365);
					}
				}
			});

			$(document).on('click', '.open-list', function(e){

				e.preventDefault();
				e.stopImmediatePropagation();
				var oid = $(this).attr('data-list-id');
				var img_cl_id = $(this).attr('data-img-cl');
				var img_op_id = $(this).attr('data-img-op');
				//console.log('list id = ' + oid);
				//console.log('close id = ' + img_cl_id);
				//console.log('open id = ' + img_op_id);
				if ($('#' + oid).hasClass('hidden')){
					$('#' + oid).removeClass('hidden');
					$('#' + img_cl_id).removeClass('hidden');
					$('#' + img_op_id).addClass('hidden');
				}else{
					$('#' + oid).addClass('hidden');
					$('#' + img_op_id).removeClass('hidden');
					$('#' + img_cl_id).addClass('hidden');
				}
			});

			$(document).on('click', 'button.add_new', function(e){
				e.stopImmediatePropagation();
				var db_tbl = $(this).attr('data-db-tbl');
				var prefix = $(this).attr('data-prefix');

				var fd = new FormData();
				fd.append('db_tbl', db_tbl);
				fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
				$.ajax({
					type		: 'POST',
					cache			: false,
					dataType : 'json',
					processData	: false,
					contentType	: false,
					url		: '<?php echo __s_lib_url__;?>_ajax/_add_new.php',
					data		: fd,
					beforeSend: function() {
						$('#ajax-loader').removeClass('hidden');
					},
					success : function(data) {
						$('#'+prefix+'-outer-container').prepend(data['page']);
					},
					complete: function(){
						$('#ajax-loader').addClass('hidden');
					},
					error: function(){
						$('#ajax-loader').addClass('hidden');
					}
				});

			});

			$(document).on('click', 'img.del-item', function(e){
				if (_sure('Are you sure you want to delete this record?')){
					e.stopImmediatePropagation();
					var db_tbl = $(this).attr('data-db-tbl');
					var id = $(this).attr('data-id');
					var del_ul = $(this).closest('li').attr('data-del-list');
					var li_id = $(this).closest('li').attr('id');
					var fd = new FormData();
					fd.append('db_tbl', db_tbl);
					fd.append('id', id);
					fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
					$.ajax({
						type		: 'POST',
						cache			: false,
						dataType : 'json',
						processData	: false,
						contentType	: false,
						url		: '<?php echo __s_lib_url__;?>_ajax/_del_item.php',
						data		: fd,
						beforeSend: function() {
							$('#ajax-loader').removeClass('hidden');
						},
						success : function(data) {
							$('#'+li_id).remove();
							if (del_ul == '1'){$('#navul'+id).remove();}
						},
						complete: function(){
							$('#ajax-loader').addClass('hidden');
						},
						error: function(){
							$('#ajax-loader').addClass('hidden');
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

			$(document).on('click', '.page-save', function(e){
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

			$('.sortable-list').sortable({
				items: 'li',
				update: function(event, ui) {
					var new_list = $(this).sortable('toArray').toString();
					var db_tbl = $(this.firstChild.nextElementSibling).attr('data-db-tbl');
					var sort_list_prefix = $(this.firstChild.nextElementSibling).attr('data-sort-list-prefix');
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
		</script>
	</body>
</html>