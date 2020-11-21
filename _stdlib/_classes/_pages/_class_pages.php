<?php
class _pages extends _setup{

	private $_page_text;

	public function __construct(){
		parent::__construct();
		echo $this->_build_page_start().$this->_build_page_text().$this->_build_page_end();
	}

	private function _build_page_start(){
		$tmp = "<section class = 'fd-col-c'>";
		return $tmp;
	}

	private function _build_page_end(){
		$tmp = "</section>";
		return $tmp;
	}

	private function _build_page_text(){
		$_sql = 'select body from __sys_pages where id = :id';
		$_d = array('id' => $this->_get_id());
		$_f = array('i');
		$_text = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		return $_text;
	}

}