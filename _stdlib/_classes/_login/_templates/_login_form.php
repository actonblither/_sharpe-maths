<?php
if (is_logged_in()){
	$_login_hidden = 'hidden';
}else{
	$_login_hidden = '';
}?>

<section id = 'login_frm' class = '<?php echo $_login_hidden;?>'>
	<script>
		$(document).ready(function(){

			$(document).on('click', '#login', function(e){
				e.preventDefault();
				e.stopImmediatePropagation();
				_login(e);
			});

			var _login = function(e){
				var un = $('#un').val();
				var pw = $('#pw').val();
				var url = '<?php echo __s_lib_url__.'_ajax/_login.php';?>';
				var fd = new FormData();
				fd.append('un', un);
				fd.append('pw', pw);
				fd.append('app_folder', '<?php echo base64_encode(__s_app_folder__);?>');
				$.ajax({
					type			: 'POST',
					cache			: false,
					processData	: false,
					contentType	: false,
					dataType: 'json',
					url: url,
					data: fd,
					beforeSend : function(){
						$('#ajax-loader').removeClass('hidden');
					},
					success : function(data){
						if (data['status'] == 1){
							$('head').append(data['page']);
						}else{
							$('#message').html(data['message']);
							$('#un').val('');
							$('#pw').val('');
						}
					},
					complete : function(){
						$('#ajax-loader').addClass('hidden');
					}
				});
			}
		});
	</script>
	<div id = 'logincontainer' class='mt100'>
		<div class = 'inner'>
			<div class = 'row'>
				<div class='label'><label for = 'un' class = 'b ml5'>Username:</label></div>
				<div class = 'input'><input class = 'login' name = 'un' id = 'un' type = 'text' placeholder = 'Your username...'></div>
				<div class= 'img'></div>
			</div>
			<div class = 'row'>
				<div class = 'label'><label for = 'pw' class = 'b ml5'>Password:</label></div>
				<div class = 'input'><input class = 'login' name = 'pw' id = 'pw' type = 'password' placeholder = 'Your password...' /></div>
				<div class= 'img'><img id = 'padlock' src = '<?php echo __s_lib_icon_url__;?>20/secure20.png' class = 'ml10 w20 h20' alt = 'Secure' /></div>
			</div>
			<div class= 'row'>
				<div class = 'label'><label for = 'pw' class = 'b ml5'></label></div>
				<div class = 'input'><button id = 'login' type = 'button' class = 'button login'>Log in</button></div>
			</div>
			<div id = 'message' class = 'row err'></div>
		</div>
	</div>

</section>