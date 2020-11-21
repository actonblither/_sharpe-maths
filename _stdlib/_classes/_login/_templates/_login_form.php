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
				var url = '<?php echo __s_app_url__.'_ajax/_login.php';?>';
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
							//window.location = 'index.php';
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

	<div class = 'flexcontainer'>
		<div class = 'logincontainer'>
	<?php
		$_img_path = __s_app_folder__.__s_app_login_screen_logo_path__;
		$_img_url = __s_app_url__.__s_app_login_screen_logo_path__;
		if (file_exists($_img_path)){?>
			<div><img src = '<?php pv($_img_url);?>' width = '<?php echo __s_app_login_screen_logo_width__;?>' alt = 'Logo' id = 'login-logo'></div>
<?php }?>
			<label for = 'un' class = 'b ml5'>Username:</label>
			<input class = 'login' name = 'un' id = 'un' type = 'text' placeholder = 'Your username...'>
			<label for = 'pw' class = 'b ml5'>Password</label>
			<div class = 'row2'>
				<input class = 'login' name = 'pw' id = 'pw' type = 'password' placeholder = 'Your password...'>
				<img id = 'padlock' src = '<?php echo __s_icon_url__;?>/20/secure20.png' style = 'margin-left:10px;' alt = 'Secure' />
			</div>
			<div class = 'row3'><button id = 'login' type = 'button' class = 'button login'>Log in</button></div>
			<p class = 'b_text ml5'><strong>Please note:</strong> All login activity is logged for security purposes.</p>
			<div id = 'message' class = 'row err'></div>
		</div>
	</div>
</section>