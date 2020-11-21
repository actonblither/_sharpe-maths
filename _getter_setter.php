<?php
include '_stdlib/_lib/_stdlib.php';

$_gs = new _getter_setter();
$_gs->_set_filename('./vars.txt');
$_gs->_set_alpha_sort(false);
$_gs->_set_return_setters(true);
$_gs->_set_return_getters(true);
$_gs->_echo_classes();

class _getter_setter{
	private $_filename;
	private $_alpha_sort = false;
	private $_return_setters;
	private $_return_getters;

	public function _echo_classes(){
		$_string = '';
		$_fh = fopen($this->_filename, 'r');
		while ($_buffer = fgets($_fh, 4096)){
			$_buffer = preg_replace('!\s+!', ' ', trim($_buffer));
			$_buffer = str_replace('private ', '', $_buffer);
			$_buffer = str_replace('protected ', '', $_buffer);
			$_buffer = str_replace('public ', '', $_buffer);
			$_buffer = str_replace(' ', '', $_buffer);
			$_semi_colon = strpos($_buffer, ';') + 1;
			$_buffer = remove_between('=', ';', $_buffer);
			if (trim($_buffer) > ''){
				$_string .= $_buffer;
			}
		}
		// remove the final semi-colon if it is there.
		if (substr($_string, -1) === ';'){
			$_string = substr($_string, 0, -1);
		}
		// create array using ; delimiter
		$_array = explode(';', $_string);
		//trim off the whitespace
		foreach ($_array as $k => $b){
			$_array[$k] = trim($b);
		}
		// Build the setters and getters
		if ($this->_return_getters){
			echo $this->_make_getter_setters($_array, 'g');
			echo '<br />';
		}
		if ($this->_return_setters){
			echo $this->_make_getter_setters($_array, 's');
		}
	}

	private function _make_getter_setters($_array, $_sg = 'g'){
		$_arr = [];
		if ($_sg == 'g'){
			$g_start = 'public function _get';
		}else{
			$g_start = 'public function _set';
		}
		foreach ($_array as $a){
			$_var_name = str_replace('$', '$this->', $a);
			$_f_n = str_replace('$', '', $a);
			$_fn_name = $g_start . $_f_n;
			if ($_sg == 'g'){
				$_arr[] = $_fn_name . '() { return ' . $_var_name . '; }';
			}else{
				$_arr[] = $_fn_name . '($_t) { ' . $_var_name . ' = $_t; }';
			}
		}
		if ($this->_alpha_sort){ asort($_arr); }
		foreach($_arr as $_a){
			echo $_a.'<br />';
		}
	}

	public function _get_filename() { return $this->_filename; }
	public function _get_alpha_sort() { return $this->_alpha_sort; }
	public function _get_return_setters() { return $this->_return_setters; }
	public function _get_return_getters() { return $this->_return_getters; }

	public function _set_filename($_t) { $this->_filename = $_t; }
	public function _set_alpha_sort($_t) { $this->_alpha_sort = $_t; }
	public function _set_return_setters($_t) { $this->_return_setters = $_t; }
	public function _set_return_getters($_t) { $this->_return_getters = $_t; }

}
?>
