<?php
// LOGGING FNS
function _cl($str, $title = ''){
	_lg($str, $title);
}

function _lg($str, $title = ''){
	$_dbh = new _db();
	$_dbh->_lg($str, $title);
}

function is_logged_in() {
	if (rvz($_SESSION['s_is_logged_in']) && rvz($_SESSION['s_auid']) > 0){
		return true;
	}else{
		return false;
	}
}

function _format_header($str){
	return '<h1>'.$str.'</h1>';
}


//FORM ELEMENT FUNCTIONS

function _build_textarea($_params){
	$_el = new _form_element();
	$_el->_set_el_field_id($_params['field_name']);
	$_el->_set_el_field_value($_params['field_value']);
	$_el->_set_db_tbl($_params['db_tbl']);
	$_el->_set_el_id_value($_params['id']);
	$_el->_set_el_width(rvz($_params['el_width'], 400));
	$_el->_set_el_height(rvz($_params['el_height'], 200));
	$_el->_set_el_width_units(rvs($_params['el_width_units'], 'px'));
	$_el->_set_el_height_units(rvs($_params['el_height_units'], 'px'));
	return $_el->_build_textarea();
}

function _build_varchar($_params){
	$_el = new _form_element();
	$_el->_set_el_field_id($_params['field_name']);
	$_el->_set_el_field_value($_params['field_value']);
	$_el->_set_db_tbl($_params['db_tbl']);
	$_el->_set_el_id_value($_params['id']);
	$_el->_set_el_width($_params['el_width']);
	$_el->_set_el_width_units($_params['el_width_units']);
	return $_el->_build_text_input();
}

function _build_checkbox($_params){
	$_el = new _form_element();
	$_el->_set_el_field_id($_params['field_name']);
	$_el->_set_el_field_value($_params['field_value']);
	$_el->_set_db_tbl($_params['db_tbl']);
	$_el->_set_el_id_value($_params['id']);
	return $_el->_build_checkbox();
}

function _build_del_header($_params, $jq = true){
	$_del_h = new _delete($_params);
	if ($jq){
		return $_del_h->_delete_jq();
	}else{
		return $_del_h->_delete_img();
	}
}

function _build_del_item($_params, $jq = true){
	//_cl($_params, 'PARAMS STDLIB');
	$_del_q = new _delete($_params);
	if ($jq){
		return $_del_q->_delete_jq();
	}else{
		return $_del_q->_delete_img();
	}
}



// String functions

function instr($needle, $haystack) {
	if (!empty($needle) && !empty($haystack)){
		$pos = strpos($haystack, $needle);
		if ($pos !== false){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function trimall($str, $charlist = ' \t\n\r\0\x0B') {
	return str_replace(str_split($charlist), '', $str);
}

function remove_between($_start, $_end, $_str){
	if (!is_string($_start)) {
		throw new InvalidArgumentException('Function argument 1 must be a string.');
	}
	if (!is_string($_end)) {
		throw new InvalidArgumentException('Function argument 2 must be a string.');
	}
	if (!is_string($_str)) {
		throw new InvalidArgumentException('Function argument 3 must be a string.');
	}
	if (instr($_end, $_str)){
		$_end_pos = strpos($_str, $_end);
		$_str = substr($_str, 0, $_end_pos).$_end;
	}
	if (instr($_start, $_str)){
		$_start_pos = strpos($_str, $_start);
		$_str = substr($_str, 0, $_start_pos).$_end;
	}
	return $_str;
}

// Array functions

function remove_empty_keys_from_array($arr) {
	$tmp = array();
	foreach ($arr as $k => $v){
		if (!empty($k)){
			$tmp[$k] = $v;
		}
	}
	return $tmp;
}


//FILTER VALIDATE INPUT FUNCTIONS

function _var_filter($var, $type) {
	if ($type == 's'){
		return htmlentities(_var_filter_str($var),ENT_QUOTES);
	}else if ($type == 'i'){
		$var = (int) $var;
		return _var_filter_int($var);
	}else if ($type == 'f'){
		$var = (float) $var;
		return _var_filter_flt($var);
	}else if ($type == 'm'){
		$var = (float) $var;
		return _var_filter_money($var);
	}else if ($type == 'b'){
		$var = (bool) $var;
		return _var_filter_int($var);
	}else if ($type == 'e'){
		return _var_filter_email($var);
	}else if ($type == 'd' || $type == 't' || $type == 'dt'){
		return _var_filter_str($var);
	}else if ($type == 'u'){
		return _var_filter_url($var);
	}else if ($type == 'h'){
		return _var_filter_html($var);
	}else{
		return null;
	}
}

//return filtered integer variable or zero (0) if not set
function rvz(&$var, $default = 0) {
	if (isset($var)){
		return _var_filter_int($var);
	}else{
		return $default;
	}
}

//return filtered boolean or false if not set
function rvb(&$var,  $default = false) {
	if (isset($var)){
		return _var_filter_bool($var);
	}else{
		return $default;
	}
}

function btz(&$var, $default = 0) {
	if (isset($var) && $var){
		return 1;
	}else{
		return $default;
	}
}

//return filtered url or empty string
function rvu(&$var, $default = '') {
	if (isset($var)){
		return _var_filter_url($var);
	}else{
		return $default;
	}
}

//return filtered variable or empty string
function rvs(&$var, $default = '') {
	if (isset($var)){
		return $var;
	}else{
		return $default;
	}
}

//return filtered string array or empty array
function rvas(&$var, $default = []){
	if (!empty($var)){
		return _var_filter_array($var, 's');
	}else{
		return $default;
	}
}

//return filtered integer array or empty array
function rvaz(&$var, $default = []) {
	if (!empty($var)){
		return _var_filter_array($var, 'i');
	}else{
		return $default;
	}
}

//return string filtered variable or null if empty or not set
function rvn( &$var) {
	if ($var === 0){return 0;}
	if (!isset($var) || empty($var)){
		return null;
	}else{
		return _var_filter_str($var);
	}
}

function etn( $tmp) {
	if ($tmp === '0'){return 0;}
	if (empty($tmp)){return null;}
	return $tmp;
}

//return variable or given value (default is empty string if value not given)
// No filtering is done here
function rv(&$var, $default = ''){
	return isset($var) ? $var : $default;
}

//output variable or given value (default is empty string if value not given)
// No filtering is done here
function pv(&$var, $default = '') {
	echo isset($var) ? $var : $default;
}

//return array or given default array
// No filtering is done here
function rva(&$var, $default = []) {
	return !empty($var) ? $var : $default;
}

//return htmlentitied string
function rs(&$var) {
	return isset($var) ? htmlentities(stripslashes($var)) : '';
}

function _var_filter_array(array $arr, $type) {
	if (!is_array($arr)){$arr = (array) $arr;}
	if (!empty($arr) && isset($type)){
		$tmp=array();
		if (!empty($arr)){
			foreach ($arr as $key=>$value){
				$tmp[$key] = _var_filter($value, $type);
			}
			return $tmp;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function _var_filter_url($url) {
	return filter_var($url, FILTER_VALIDATE_URL);
}

function _var_filter_int($int) {
	return filter_var($int, FILTER_VALIDATE_INT);
}

function _var_filter_bool($bool) {
	return filter_var($bool, FILTER_VALIDATE_BOOLEAN);
}

function _var_filter_flt($flt) {
	$flt = (float) $flt;
	return filter_var($flt, FILTER_VALIDATE_FLOAT);
}

function _var_filter_money($flt) {
	$flt = (float) $flt;
	$tmp = filter_var($flt, FILTER_VALIDATE_FLOAT);
	return $tmp;
}

function _var_filter_html($str) {
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$str = $purifier->purify($str);
	return $str;
}

function _var_filter_str($str) {
	$str = filter_var($str, FILTER_SANITIZE_STRING);
	return $str;
}

function _var_filter_email($email) {
	return filter_var($email, FILTER_SANITIZE_EMAIL);
}

function _var_validate_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function _var_filter_mq($str) {
	return filter_var($str,FILTER_SANITIZE_MAGIC_QUOTES);
}


// FORM ELEMENT FUNCTIONS

function checked(&$var, $set_value = 1, $unset_value = 0) {
	if (empty(rv($var))) {
		$var = $unset_value;
	} else {
		$var = $set_value;
	}
}

function frmchecked(&$var, $true_value = 'checked', $false_value = '') {
	if (rv($var)) {
		return $true_value;
	} else {
		return $false_value;
	}
}

function p_sel() {
	return "selected = 'selected'";
}

// HEADER FUNCTIONS
function redirect( $url, $message = '', $delay = 0) {
	return "<meta http-equiv='Refresh' content='".$delay."'; url = ".$url.">";
}

// DATE FUNCTIONS
function now($format = 'Y-m-d H:i:s'){
	//useful little public function to replace the MYSQL now() public function when using prepared statements.
	return date($format);
}

function is_folder_empty($dir)  {
	$handle = opendir($dir);
	while (false !== ($entry = readdir($handle))) {
		if ($entry != '.' && $entry != '..') {
			return false;
		}
	}
	return true;
}

function _set_browser_tab_title() {
	if (isset($_SESSION['s_main'])){
		$_dbh = new _db();
		if (!empty($_SESSION['s_id'])){
			$sql = 'select title from _app_nav_routes where id = :id';
			$_d = array('id' => $_SESSION['s_id']);
			$_f = array('i');
			$title = $_dbh->_fetch_db_datum_p($sql, $_d, $_f);
		}else{
			$tmp = 'SHARPE-MATHS ONLINE RESOURCES';
		}

		$tmp = "<script>
			$(document).ready(function(){
				$('title').html('".$title."');
			});
			</script>";
	}
	return $tmp;
}


function handle_uncaught_exception($e) {
	echo "An error occurred. The culprit will be tracked down and summarily dealt with. The developer has been notified and is working on it.";

	$error = $message = date("Y-m-d H:i:s - ");
	$error .= $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine() . "\n";

	// Log details of error in the _php_code_errors.log file
	$err = new _db();
	$err->_logger($e, 'UNCAUGHT EXCEPTION LOG');
}


function yes_no_unset($q, $y_title = 'y', $n_title = 'n') {
	if (!isset($q)){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/notset16.png' alt = 'Not set' title = 'Not set' />";
	}else if ($q || $q === 'y' || $q === 'yes' || $q === 1){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/yes16.png' class = 'ttip' alt = 'Yes' title = '".$y_title."' />";
	}else if ($q === false || $q === 'n' || $q === 'no' || $q === 0){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/no16.png' class = 'ttip' alt = 'No' title = '".$n_title."' />";
	}
	return $image;
}

function yes_no_na($q, $y_title = 'y', $n_title = 'n') {
	if (!isset($q)){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/na16.png' alt = 'Not set' title = 'Not set' />";
	}else if ($q || $q === 'y' || $q === 'yes' || $q === 1){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/yes16.png' class = 'ttip' alt = 'Yes' title = '".$y_title."' />";
	}else if ($q === false || $q === 'n' || $q === 'no' || $q === 0){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/no16.png' class = 'ttip' alt = 'No' title = '".$n_title."' />";
	}
	return $image;
}

function yes_no_partial($q) {
	if ($q === 'partial'){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/partial16.png' class = 'ttip' alt = 'Partial' title = 'Partial' />";
	}else if ($q === 'blank'){
		$image = '';
	}else if ($q || $q === 'y' || $q === 'yes' || $q === 1){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/complete16.png' class = 'ttip' alt = 'Complete' title = 'Registration complete' />";
	}else if ($q === false || $q === 'n' || $q === 'no' || $q === 0){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/no16.png' class = 'ttip' alt = 'No' title = 'No' />";
	}else{
		$image = '';
	}
	return $image;
}

function yes_no_na_partial($q) {
	if (!isset($q)){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/na16.png' alt = 'Not set' title = 'Not set' />";
	}else if ($q === 'partial'){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/partial16.png' class = 'ttip' alt = 'Partial' title = 'Partial' />";
	}else if ($q === 'blank'){
		$image = '';
	}else if ($q || $q === 'y' || $q === 'yes' || $q === 1){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/yes16.png' class = 'ttip' alt = 'Yes' title = 'Yes' />";
	}else if ($q === false || $q === 'n' || $q === 'no' || $q === 0){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/no16.png' class = 'ttip' alt = 'No' title = 'No' />";
	}
	return $image;
}

function yes_no($q, $y_title = 'Yes', $n_title = 'No') {
	if ($q){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/yes16.png' class = 'ttip' alt = 'Yes' title = '".$y_title."' />";
	}else{
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/no16.png' class = 'ttip' alt = 'No' title = '".$n_title."' />";
	}
	return $image;
}

function live_archive($_live) {
	if ($_live){
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/live16.png' class = 'ttip' alt = 'Live' title = 'Live' />";
	}else{
		$image = "<img src = '".__s_lib_url__."/_images/_icons/16/archive16.png' class = 'ttip' alt = 'Archived' title = 'Archived' />";
	}
	return $image;
}


function yes_no_text($q) {
	if ($q == 1){
		$t = 'Yes';
	}else{
		$t = 'No';
	}
	return $t;
}


function get_icon($img, $title){
	$icon_path = __s_lib_url__.'/_images/_icons/';
	$image = $icon_path.$img;
	if (file_exists($image)){
		return '<img src = "'.$image.'" class = "ttip" alt = "'.$img.'" title = "'.$title.'" />';
	}else{
		return false;
	}
}

function convert_align_to_flex_class($align) {
	if ($align == 'center' || $align == 'c'){
		return 'c';
	}else if ($align == 'right' || $align == 'r'){
		return 'r';
	}else if ($align == 'left' || $align == 'l'){
		return 'l';
	}else{
		return false;
	}
}

function get_valign_class($c) {
	if ($c == 't'){return 'vt';}
	if ($c == 'm'){return 'vm';}
	if ($c == 'b'){return 'vb';}
	return '';
}

function get_container_valign_class($c) {
	if ($c == 't'){return 'cvt';}
	if ($c == 'm'){return 'cvm';}
	if ($c == 'b'){return 'cvb';}
	return '';
}

function _get_data_field_type($str) {
	if ($str == '::') return false;
	$tmp = explode('::', $str);
	return $tmp[0];
}

function _get_data_field($str) {
	if ($str == '::') return false;
	$tmp = explode('::', $str);
	return $tmp[3];
}

function write_ini_file($file, $array = []) {
	// check first argument is string
	if (!is_string($file)) {
		throw new \InvalidArgumentException('Function argument 1 must be a string.');
	}

	// check second argument is array
	if (!is_array($array)) {
		throw new \InvalidArgumentException('Function argument 2 must be an array.');
	}

	// process array
	$data = array();
	foreach ($array as $key => $val) {
		if (is_array($val)) {
			$data[] = "[$key]";
			foreach ($val as $skey => $sval) {
				if (is_array($sval)) {
					foreach ($sval as $_skey => $_sval) {
						if (is_numeric($_skey)) {
							$data[] = $skey.'[] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
						} else {
							$data[] = $skey.'['.$_skey.'] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
						}
					}
				} else {
					$data[] = $skey.' = '.(is_numeric($sval) ? $sval : (ctype_upper($sval) ? $sval : '"'.$sval.'"'));
				}
			}
		} else {
			$data[] = $key.' = '.(is_numeric($val) ? $val : (ctype_upper($val) ? $val : '"'.$val.'"'));
		}
		// empty line
		$data[] = null;
	}

	// open file pointer, init flock options
	$fp = fopen($file, 'w');
	$retries = 0;
	$max_retries = 100;

	if (!$fp) {
		return false;
	}

	// loop until get lock, or reach max retries
	do {
		if ($retries > 0) {
			usleep(rand(1, 5000));
		}
		$retries += 1;
	} while (!flock($fp, LOCK_EX) && $retries <= $max_retries);

	// couldn't get the lock
	if ($retries == $max_retries) {
		return false;
	}

	// got lock, write data
	fwrite($fp, implode(PHP_EOL, $data).PHP_EOL);

	// release lock
	flock($fp, LOCK_UN);
	fclose($fp);

	return true;
}

class _img_string{
	private $_img_id;
	private $_img_class;
	private $_img_src;
	private $_img_alt;
	private $_img_title;
	private $_img_link_target;
	private $_img_target_url;

	public function _build_string(){
		if ($this->_img_link_target == '_blank'){ $_title_suffix = ' (Opens in a new tab.)';}else{ $_title_suffix = ' (Opens in this tab.)';}
		$_img_str = "<img id = '".$this->_img_id."' ";
		if (!empty($this->_img_class)){ $_img_str .= "class = '".$this->_img_class."' ";}
		if (!empty($this->_img_src)){ $_img_str .= "src = '".$this->_img_src."' ";}
		if (!empty($this->_img_alt)){ $_img_str .= "alt = '".$this->_img_alt."' ";}
		if (!empty($this->_img_title)){ $_img_str .= "title = '".$this->_img_title.$_title_suffix."' ";}
		if (!empty($this->_img_target_url)){
			$_img_str .= "onclick = 'window.open(\"".$this->_img_target_url."\", \"".$this->_img_link_target."\");'";
		}
		$_img_str .= "/>";
		//_lg($_img_str);
		return $_img_str;
	}

	public function _set_img_id($t) { $this->_img_id = $t; }
	public function _set_img_class($t) { $this->_img_class = $t; }
	public function _set_img_src($t) { $this->_img_src = $t; }
	public function _set_img_alt($t) { $this->_img_alt = $t; }
	public function _set_img_title($t) { $this->_img_title = $t; }
	public function _set_img_target_url($t) { $this->_img_target_url = $t; }
	public function _set_img_link_target($t) { $this->_img_link_target = $t; }
}

?>