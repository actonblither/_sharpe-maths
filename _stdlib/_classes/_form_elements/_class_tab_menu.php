<?php
//These are hard top tabs
class _tab_menu extends _std_lib{

	private $str;
	private $_code;
	private $_modes;
	private $_mode_array;
	private $_titles;
	private $_links;
	private $_fields;
	private $_field;
	private $_default;
	private $_class;
	private $_help;

	/* STRUCTURE
	 $titles is an array of strings which will appear on the tabs
	 $field is the assoc name of the $_GET vars
	 $links is an array of links for where the tabs go to
	 $fields is an array of strings like 'edit_insert_update' separated by underscores.
	 These are $_GET['field'] values for each tab.
	 $
	 $default is the tab which will be accessed by default.
	 */

	public function __construct($titles, $links, $fields, $field, $default, $class, $help){
		$this->_main = rvs($_GET['main']);
		$this->_mode = rvs($_GET['mode'], $field);
		$this->_field = rvs($_GET[$field]);

		$this->_titles = $titles;
		$this->_links = $links;
		$this->_fields = $fields;
		$this->_default = $default;
		$this->_class = $class;
		$this->_help = $help;
		if (!isset($this->_field) || empty($this->_field)){$this->_field = $this->_default;}
	}

	public function _make_tabs(){
		$this->str = '<nav class = "main-tabs">'.PHP_EOL;
		$this->str .= '<div class = "tab-gap"><div class = "gap-a"></div><div class = "base"></div></div>'.PHP_EOL;

		//loop through the arrays to create the tab menu
		for ($i=0; $i < count($this->_titles); $i++){
			if (empty($this->_class[$i])){$this->_class[$i] = 'gen';}
			if ($this->_titles[$i] == '+'){$el_id = $this->_list_id_prefix.'_add_new';}else{$el_id = 'aa'.$i;}
			$this->str .= '<div class = "tab-container">';
			$this->_field_array = explode('::', $this->_fields[$i]);
			if ($el_id != $this->_list_id_prefix.'_add_new'){
				$this->str .= $this->_build_tab_btn($i);
			}else{
				$this->str .= "<button id = '".$this->_list_id_prefix."_add_new' class = 'add_new tab_add_btn point ttip' title = '".rv($this->_help[$i])."' type = 'button'>+</button>";
			}
			$this->str .= '<div class = "base"></div></div>'.PHP_EOL;
			$this->str .= '<div class = "tab-gap '.$this->_class[$i].'"><div class = "gap-a"></div><div class = "base"></div></div>'.PHP_EOL;
		}
		$this->str .= '<div class = "tab-gap"><div class = "gap-a"></div><div class = "base"></div></div>'.PHP_EOL;
		$this->str .= '</nav>'.PHP_EOL;
		$this->_code = $this->str;
		return $this->_code;
	}


	public function _build_tab_btn($i){
		$str = '<a class = "ttip '.$this->_class[$i];
		$str.=$this->is_selected($this->_field_array, $this->_field, ' ');
		$str .= '"';
		$str .= ' href = "'.$this->_links[$i];
		$str .= '" title = "'.rv($this->_help[$i]).'">'.rv($this->_titles[$i]).'</a>'.PHP_EOL;
		return $str;
	}

	private function is_selected($val, $mode, $space = ''){
		if (in_array($mode, $val)){return $space.'curr_sel';}
	}

	public function _get_code(){return $this->_code;}
	public function _get_titles() {return $this->_titles;}
	public function _get_links() {return $this->_links;}
	public function _get_fields() {return $this->_fields;}
	public function _get_field() {return $this->_field;}
	public function _get_default() {return $this->_default;}
	public function _get_class() {return $this->_class;}

	public function _set_titles($_titles) {$this->_titles = $_titles;}
	public function _set_links($_links) {$this->_links = $_links;}
	public function _set_fields($_fields) {$this->_fields = $_fields;}
	public function _set_field($_field) {$this->_field = $_field;}
	public function _set_default($_default) {$this->_default = $_default;}
	public function _set_class($_class) {$this->_class = $_class;}

}//END tabmenu class
