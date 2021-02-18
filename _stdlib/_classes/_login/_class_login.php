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
		$_auid = $this->_check_credentials();
		if ($_auid > 0){
			$_SESSION['s_auid'] = (int) $_auid;
			$_t = '__sys_admin_user_logon_history';
			$_d = array(
				'logon_dt' => now(),
				'username' => $this->_un,
				'auid' => $_auid
			);
			$_f = array('s', 's', 'i');
			$insert_id = $this->_dbh->_insert($_t, $_d, $_f);
			// Now fetch priv and other settings from __sys_admin_users and initialize session vars.
			$sql = 'select priv, s_show_tooltips, s_sticky_navbar from __sys_admin_users where id = :id';
			$_d = array('id' => $_SESSION['s_auid']);
			$_f = array('i');
			$_row = $this->_dbh->_fetch_db_row_p($sql, $_d, $_f);
			$_SESSION['s_priv'] = $_row['priv'];
			redirect(__s_app_url__);
			return $_auid;

		}else{
			return false;
		}
	}

	public function _logout(){
		if (is_logged_in()){
			$_t = '__sys_admin_user_logon_history';
			$_d = array('logout_dt' => now());
			$_f = array('s');
			$_w = array('auid' => $_SESSION['s_auid']);
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

		$_SESSION['s_auid'] = 0;
		$_SESSION['s_auid_tmp'] = 0;
		$_SESSION['s_priv'] = 0;

	}

	private function _get_auid_from_username($un){
		$sql = 'select id from __sys_admin_users where username = :username';
		$_d = array('username' => $un);
		$_f = array('s');
		$_id = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		return $_id;
	}

	private function _check_credentials(){
		$sql = 'select id, pw from __sys_admin_users where username = :username';
		$_d = array('username' => $this->_un);
		$_f = array('s');
		$_row = $this->_dbh->_fetch_db_row_p($sql, $_d, $_f);
		if (password_verify($this->_pw, $_row['pw'])){
			$_SESSION['s_auid'] = $_row['id'];
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
		return $tmp;
	}

	public function _set_un($t){ $this->_un = $t; }
	public function _set_pw($t){ $this->_pw = $t; }

}

?>