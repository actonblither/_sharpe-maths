<?php
class _contact{
	private $_page_title;
	private $_page_body;

	public function __construct(){
		$this->_build_page_title();
		$this->_build_page_body();
	}

	public function _build_contact_form(){
		return $this->_page_title.$this->_page_body;
	}

	private function _build_page_body(){
		ob_start();
		include (__s_lib_folder__."_classes/_contact/_templates/_contact_form.php");
		$this->_page_body = ob_get_clean();
	}

	private function _build_page_title(){
		$_title = new _title_bar();
		$_title->_set_title('Contact form');
		$_title->_set_img('contact32.png');
		$_title->_set_img_alt('Contact');
		$this->_page_title = $_title->_build_title_bar();
	}




}