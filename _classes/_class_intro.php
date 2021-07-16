<?php
class _intro{
	private $_dbh;
	private $_topic_id;
	private $_intro;
	private $_make_intro_tab = false;

	public function __construct($_tid = null){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
	}

	public function _fetch_intro_text(){
		return $this->_build_intro_text();
	}

	private function _build_intro_text(){
		$_sql = 'select intro from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$this->_intro = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);

		if (is_logged_in()){
			$this->_build_intro_edit();
		}else{
			$_tip = new _tips($this->_intro, $this->_topic_id);
			$this->_intro = $_tip->_get_return_txt();
		}
		if (!empty($this->_intro) || is_logged_in()){
			$this->_set_make_intro_tab(true);
		}
		return $this->_intro;
	}

	private function _build_intro_edit(){
		$_el = new _form_element();
		$_el->_set_el_field_id('intro');
		$_el->_set_el_field_value($this->_intro);
		$_el->_set_db_tbl('_app_topic');
		$_el->_set_el_id_value($this->_topic_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(400);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('px');
		$this->_intro = $_el->_build_ckeditor();

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('_app_topic');
		$_el_btn->_set_el_id_value($this->_topic_id);
		$_el_btn->_set_el_field_id('intro');
		$_el_btn->_set_el_field_value('Save topic introduction');
		$_el_btn->_set_el_width(200);
		$_el_btn->_set_el_width_units('px');
		$_el_btn->_build_save_btn();

		$this->_intro .= $_el_btn->_build_save_btn();
	}

	public function _set_topic_id($_t){$this->_topic_id = $_t;}
	public function _set_make_intro_tab($_t) { $this->_make_intro_tab = $_t; }
	public function _get_make_intro_tab() { return $this->_make_intro_tab; }

}

?>
