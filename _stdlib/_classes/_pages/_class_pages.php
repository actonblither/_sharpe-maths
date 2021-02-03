<?php
class _pages extends _setup{

	private $_page_text;
	private $_page_id;

	public function __construct($_id = null){
		parent::__construct();
		if ($_id){$this->_page_id = $_id;}
	}

	public function _build_page(){
		if (is_logged_in()){
			return $this->_build_edit_page();
		}else{
			return $this->_build_page_start().$this->_build_page_text().$this->_build_page_end();
		}
	}

	private function _build_edit_page(){
		$_page_text = $this->_build_page_text();
		$_id = $this->_get_id();
		$_el = new _form_element();
		$_el->_set_el_field_id('body');
		$_el->_set_el_field_value($_page_text);
		$_el->_set_db_tbl('__sys_pages');
		$_el->_set_el_id_value($_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(100);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('%');

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('__sys_pages');
		$_el_btn->_set_el_id_value($this->_get_id());
		$_el_btn->_set_el_field_id('body');
		$_el_btn->_set_el_field_value('Save page');
		$_el_btn->_set_el_width(120);
		$_el_btn->_set_el_field_class('mb5');
		$_el_btn->_set_el_width_units('px');
		$_el_btn->_build_save_btn();

		return $_el_btn->_build_save_btn().$_el->_build_ckeditor();
	}

	private function _build_page_start(){
		$tmp = "<section class = 'fd-col-l ml10'>";
		return $tmp;
	}

	private function _build_page_end(){
		$tmp = "</section>";
		return $tmp;
	}

	private function _build_page_text(){
		if (!isset($this->_page_id)){
			$_sql = "select page_id from _app_nav_routes where id = :id";
			$_d = array('id' => $this->_get_id());
			$_f = array('i');
			$this->_page_id = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		}
		$_sql = 'select body from __sys_pages where id = :id';
		$_d = array('id' => $this->_page_id);
		$_f = array('i');
		$_text = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		//_cl($_text, 'PAGE TEXT');
		return $_text;
	}

}