<?php
class _contact{

	private $_dbh;

	public function __construct(){
		$this->_dbh = new _db();
		$this->_build_contact_form();
	}


	private function _build_contact_form(){
		include (__s_lib_folder__."_classes/_contact/_templates/_contact_form.php");
	}
}