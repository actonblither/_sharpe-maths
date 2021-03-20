<?php
class _setup {
	protected $_id;
	protected $_main;
	protected $_mode;
	protected $_dbh;
	protected $_cfg_title;
	protected $_cfg_path;
	protected $_is_logged_in;

	public function __construct(){
		$this->_dbh = new _db();
		$this->_main = rvs($_REQUEST['main'], 'page');
		$this->_id = rvz($_REQUEST['id'], 1);
		$this->_is_logged_in = is_logged_in();
	}

	public function _fetch_current_admin_user_name(){
		$sql = 'select concat(firstname, " ", lastname) from __sys_admin_users where id = :id';
		$_d = array('id' => $_SESSION['s_auid']);
		$_f = array('i');
		$name = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		return stripslashes($name);
	}

	public function _fetch_access_name(){
		$sql = 'select title from __sys_list_admin_users_security_codes where id = :id';
		$_d = array('id' => $_SESSION['s_priv']);
		$_f = array('i');
		$p = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		return stripslashes($p);
	}

	public function _fetch_access_code(){
		$sql = 'select code from __sys_list_admin_users_security_codes where id = :id';
		$_d = array('id' => $_SESSION['s_priv']);
		$_f = array('i');
		$p = $this->_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		return stripslashes($p);
	}

	public function _get_id() { return $this->_id; }
	public function _get_main() { return $this->_main; }
	public function _get_mode() { return $this->_mode; }
	public function _get_dbh() { return $this->_dbh; }
	public function _get_cfg_title() { return $this->_cfg_title; }
	public function _get_cfg_path() { return $this->_cfg_path; }

	public function _set_id($_t) { $this->_id = $_t; }
	public function _set_main($_t) { $this->_main = $_t; }
	public function _set_mode($_t) { $this->_mode = $_t; }
	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_cfg_title($_t) { $this->_cfg_title = $_t; }
	public function _set_cfg_path($_t) { $this->_cfg_path = $_t; }
}

?>