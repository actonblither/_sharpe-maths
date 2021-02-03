<?php
class _title_bar{
	private $_img;
	private $_title;

	public function __construct(){}

	public function _build_title_bar(){
		$_tmp = "<div class = 'page-title'>";
		$_img = $this->_get_img();
		$_title = $this->_get_title();
		if (!empty($_img)){
			$_img = "_images/_title_bar_img/".$_img;
			$_img_path = __s_app_folder__.$_img;
			$_img_url = __s_app_url__.$_img;
			if (file_exists($_img_path)){
				$_tmp .= "<img src = '".$_img_url."' alt = '".ucwords($_title)."' />";
			}
		}
		$_tmp .= $_title . '</div>';
		return $_tmp;
	}

	public function _get_img() { return $this->_img; }
	public function _get_title() { return $this->_title; }


	public function _set_img($_t) { $this->_img = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }


}

class _img{

	private $_url;
	private $_class;
	private $_img_name;
	private $_title;
	private $_alt;

	public function __construct(){}

	private function _fetch_src(){
		return $this->_url.$this->_img_name;
	}

	public function _fetch_img(){
		$_class = $this->_fetch_class_str();
		$tmp = "<img src = '".$this->_fetch_src()."' alt = '".$this->_alt."' class = '".$_class."' title = '".$this->_title."' />";
		return $tmp;
	}
	private function _fetch_class_str(){
		$_class = '';
		foreach ($this->_class as $_c){
			$_class .= $_c." ";
		}
		$_class = substr($_class, 0, -1);
		return $_class;
	}


	public function _get_class() { return $this->_class; }
	public function _get_img_name() { return $this->_img_name; }
	public function _get_title() { return $this->_title; }
	public function _get_alt() { return $this->_alt; }
	public function _get_url() { return $this->_url; }

	public function _set_class($_t) { $this->_class = $_t; }
	public function _set_img_name($_t) { $this->_img_name = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }
	public function _set_alt($_t) { $this->_alt = $_t; }
	public function _set_url($_t) { $this->_url = $_t; }
}
?>