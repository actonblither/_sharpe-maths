<?php
class _title_bar{
	private $_img;
	private $_img_alt;
	private $_title;

	public function __construct(){}

	public function _build_title_bar(){
		$_tmp = "<div class = 'page-title mb10'>";
		$_img = $this->_get_img();
		$_title = $this->_get_title();
		if (!empty($_img)){
			$_img = "_images/_icons/32/".$_img;
			$_img_path = __s_lib_folder__.$_img;
			$_img_url = __s_lib_url__.$_img;
			if (file_exists($_img_path)){
				$_tmp .= "<img class='ml20 mr20' src = '".$_img_url."' alt = '".ucwords($this->_img_alt)."' />";
			}
		}
		$_tmp .= "<span class='page-title-text'>" . ucwords($_title) . '</span></div>';
		return $_tmp;
	}

	public function _get_img() { return $this->_img; }
	public function _get_title() { return $this->_title; }


	public function _set_img($_t) { $this->_img = $_t; }
	public function _set_img_alt($_t) { $this->_img_alt = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }


}

class _filter{
	private $_label = 'Filter';
	private $_input_width_class = 'w300';
	private $_input_css_id = 'sfilter';
	private $_input_title = 'Type into this field to filter the list down to those records which contains those letters or words.';

	public function __construct(){}

	public function _build_filter(){
		$tmp = "<div class='filter-row'><label class='ml10' for = '".$this->_input_css_id."'><h4>".$this->_label.":</h4></label>
		<input id = '".$this->_input_css_id."' class = '".$this->_input_width_class." mr5 ml5 ttip' title = '".$this->_input_title."' type = 'text' /></div>";
		return $tmp;
	}

	public function _get_label() { return $this->_label; }
	public function _get_input_width_class() { return $this->_input_width_class; }
	public function _get_input_css_id() { return $this->_input_css_id; }
	public function _get_input_title() { return $this->_input_title; }

	public function _set_label($_t) { $this->_label = $_t; }
	public function _set_input_width_class($_t) { $this->_input_width_class = $_t; }
	public function _set_input_css_id($_t) { $this->_input_css_id = $_t; }
	public function _set_input_title($_t) { $this->_input_title = $_t; }
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