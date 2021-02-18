<?php
class _pages extends _setup{

	private $_page_text;
	private $_page_route_id;
	private $_page_id;
	private $_page_title;
	private $_page_body;

	public function __construct($_id = 1){
		parent::__construct();
		if ($_id){
			$this->_page_route_id = $_id;
			$this->_fetch_page_id();
			$this->_fetch_page_title();
			$this->_fetch_page_body();
		}
	}

	public function _build_page(){
		if (is_logged_in()){
			return $this->_page_title.$this->_build_edit_page();
		}else{
			return $this->_build_page_start().$this->_page_title.$this->_page_body.$this->_build_page_end();
		}
	}

	private function _build_edit_page(){
		$_el = new _form_element();
		$_el->_set_el_field_id('body');
		$_el->_set_el_field_value($this->_page_body);
		$_el->_set_db_tbl('__sys_pages');
		$_el->_set_el_id_value($this->_page_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(100);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('%');

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('__sys_pages');
		$_el_btn->_set_el_id_value($this->_page_id);
		$_el_btn->_set_el_field_id('body');
		$_el_btn->_set_el_field_value('Save page');
		$_el_btn->_set_el_width(120);
		$_el_btn->_set_el_field_class('mb15');
		$_el_btn->_set_el_width_units('px');

		return $_el_btn->_build_save_btn().$_el->_build_ckeditor();
	}

	private function _build_page_start(){
		$tmp = "<section class = 'fd-col-l'>";
		return $tmp;
	}

	private function _build_page_end(){
		$tmp = "</section>";
		return $tmp;
	}

	private function _fetch_page_id(){
		$_sql = "select page_id from _app_nav_routes where id = :id";
		$_d = array('id' => $this->_page_route_id);
		$_f = array('i');
		$this->_page_id = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

	private function _fetch_page_title(){
		$_sql = "select title from __sys_pages where id = :id";
		$_d = array('id' => $this->_page_id);
		$_f = array('i');
		$_page_title = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		$_title = new _title_bar();
		$_title->_set_title($_page_title);
		$_title->_set_img('glossary32.png');
		$_title->_set_img_alt('Glossary');
		$this->_page_title = $_title->_build_title_bar();
	}


	private function _fetch_page_body(){
		$_sql = 'select body from __sys_pages where id = :id';
		$_d = array('id' => $this->_page_id);
		$_f = array('i');
		$this->_page_body = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

}