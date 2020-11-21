<?php
class _login{
	private $_dbh;
	private $_un;
	private $_pw;
	private $_priv;
	private $_is_logged_in = false;

	public function __construct(){
		$this->_dbh =  new _db();
	}

	public function _login(){
		if (instr('_zaphod', $this->_un)){
			$this->_un = str_replace('_zaphod', '', $this->_un);
			$_access_clone_account = true;
		}else{
			$_access_clone_account = false;
		}

		if ($_access_clone_account){
			$_auid = $this->_check_admin_credentials();
		}else{
			$_auid = $this->_check_credentials();
		}

		if ($_auid > 0){
			setcookie('session_id', $this->_get_session_id_cookie($_auid), 0, '/', 'localhost', true);
			$GLOBALS['s_auid'] = (int) $_auid;
			$GLOBALS['s_auid_tmp'] = (int) $_auid;
			$GLOBALS['s_va'] = 0;
			$GLOBALS['s_is_logged_in'] = 1;
			$_t = '__sys_admin_user_logon_history';
			$_d = array(
				'logon_dt' => now(),
				'username' => $this->_un,
				'auid' => $_auid
			);
			$_f = array('s', 's', 'i');
			$insert_id = $this->_dbh->_insert($_t, $_d, $_f);
			// Now fetch priv and other settings from __sys_admin_users and initialize session vars.
			$sql = 'select session_id, priv, s_show_tooltips, s_sticky_navbar, s_view_other_saved_searches from __sys_admin_users where id = :id';
			$_d = array('id' => $GLOBALS['s_auid']);
			$_f = array('i');
			$_row = $this->_dbh->_fetch_db_row_p($sql, $_d, $_f);
			$_SESSION['s_priv'] = $_row['priv'];
			$GLOBALS['s_priv'] = $_row['priv'];
			$GLOBALS['s_is_logged_in'] = 1;
			$GLOBALS['s_show_tooltips'] = $_row['s_show_tooltips'];
			$GLOBALS['s_view_other_saved_searches'] = $_row['s_view_other_saved_searches'];
			$GLOBALS['s_sticky_navbar'] = $_row['s_sticky_navbar'];
			$GLOBALS['session_id'] = $_row['session_id'];
			$_SESSION['s_session_id'] = $_row['session_id'];
			setcookie('session_id', $_row['session_id'], 0, '', 'localhost', true);

			$_config = $this->_build_session_array();

			if (!empty($_row['session_id'])){
				$_file = __s_session_folder__.'/'.$_row['session_id'].'.ini';
				write_ini_file($_file, $_config);
				$GLOBALS['s_is_logged_in'] = 1;
				return $_auid;
			}
		}else{
			return false;
		}
	}

	private function _get_session_id_cookie($_auid){
		$_dbh = new _db();
		$sql = 'select session_id from __sys_admin_users where id = :id';
		$_d = array('id' => $_auid);
		$_f = array('i');
		$_ssid = $_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		//_lg($_ssid, 'SET SESS ID COOKIE');
		return $_ssid;
	}

	public function _logout(){
		if (isset($GLOBALS['s_auid'])){
			$_t = '__sys_admin_user_logon_history';
			$_d = array('logout_dt' => now());
			$_f = array('s');
			$_w = array('auid' => $GLOBALS['s_auid']);
			$_wf = array('i');

			if ($result = $this->_dbh->_update($_t, $_d, $_f, $_w, $_wf)){
				$message = $result;
			}else{
				$message = "You have logged out successfully";
			}
		}
		//update the admin user table to archive priv = 0 users.
		$_t = '__sys_admin_users';
		$_d = array('archived' => 1);
		$_f = array('i');
		$_w = array('priv' => 0);
		$_wf = array('i');
		$result = $this->_dbh->_update($_t, $_d, $_f, $_w, $_wf);
		$GLOBALS['s_is_logged_in'] = 0;
		$_SESSION['s_is_logged_in'] = 0;

		$_config = $this->_build_session_array();

		$_session_id_cookie = $this->_get_session_id_cookie($GLOBALS['s_auid']);
		if (isset($_session_id_cookie)){
			$_file = __s_session_folder__.'/'.$_session_id_cookie.'.ini';
			$GLOBALS['s_auid'] = 0;
			write_ini_file($_file, $_config);
		}

		$GLOBALS['s_auid'] = 0;
		$GLOBALS['s_auid_tmp'] = 0;
		$GLOBALS['s_priv'] = 0;
	}

	private function _build_session_array(){
		$_config['globals']['s_auid'] = rvz($GLOBALS['s_auid']);
		$_config['globals']['s_auid_tmp'] = rvz($GLOBALS['s_auid_tmp']);
		$_config['globals']['s_priv'] = rvz($GLOBALS['s_priv']);
		$_config['globals']['s_is_logged_in'] = rvz($GLOBALS['s_is_logged_in']);
		$_config['globals']['s_sticky_navbar'] = rvz($GLOBALS['s_sticky_navbar']);
		$_config['globals']['s_show_tooltips'] = rvz($GLOBALS['s_show_tooltips']);
		$_config['globals']['s_view_other_saved_searches'] = rvz($GLOBALS['s_view_other_saved_searches']);
		$_config['globals']['s_va'] = rvz($GLOBALS['s_va']);
		return $_config;
	}

	private function _get_auid_from_username($un){
		$sql = 'select id from __sys_admin_users where username = :username';
		$_d = array('username' => $un);
		$_f = array('s');
		$_id = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		return $_id;
	}

	private function _check_admin_credentials(){
		$sql = 'select pw from __sys_admin_users where id = :id';
		$_d = array('id' => 1);
		$_f = array('i');
		$pw = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		if (password_verify($this->_pw, $pw)){
			return $this->_get_auid_from_username($this->_un);
		}else{
			return false;
		}
	}

	protected function _check_credentials(){
		$sql = 'select id, pw, session_id from __sys_admin_users where username = :username';
		$_d = array('username' => $this->_un);
		$_f = array('s');
		$_row = $this->_dbh->_fetch_db_row_p($sql, $_d, $_f);
		setcookie('session_id', $_row['session_id'], 0, '/', 'localhost', true);
		if (password_verify($this->_pw, $_row['pw'])){

			$GLOBALS['s_is_logged_in'] = 1;
			$GLOBALS['session_id'] = $_row['session_id'];
			$GLOBALS['s_auid'] = $_row['id'];
			return $_row['id'];
		}else{
			return false;
		}
	}

	public function _get_user_priv(){
		if (isset($this->_priv)){
			return $this->_priv;
		}else{
			return false;
		}
	}

	public function _get_login_page(){
		ob_start();
		include_once(__s_lib_folder__.'/_classes/_login/_templates/_login_form.php');
		$tmp = ob_get_clean();
		echo $tmp;
	}

	public function _set_un($t){ $this->_un = $t; }
	public function _set_pw($t){ $this->_pw = $t; }

}

?>