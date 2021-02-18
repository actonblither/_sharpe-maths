<?php
/* Variables:
 * $_field name/id
 * $_field value
 * $_place_holder ('Not set...' grey in input field)
 * $_start_empty (t/f)
 * $_show_clear_image (t/f)
 *
 * $params = array(
 * 	'name' => 'date_element_name',
 * 	'value' => $field_value,
 * 	'place_holder' => 'Not set...',
 * 	'start_empty' => t/f,
 *  'start_now' => t/f,
 * 	'show_clear_img' => t/f,
 * 	'dt_format' => 'd-m-Y H:i',
 * 	'input_width' => 120,
 * 	'class' => 'change_dq_data'
 * );
 *
 * $d = new _datetime($params);
 * echo $d->_get_date();
 * */
class _datetime{
	private $_name;
	private $_value;
	private $_place_holder;
	private $_start_empty;
	private $_start_now;
	private $_show_clear_img;
	private $_show_time_picker;
	private $_year_range;
	private $_dt_format;
	private $_input_width;
	private $_class;
	private $_date;
	private $_now;
	private $_hidden;
	private $_display;

	public function __construct($params = array()){
		$this->_name = rv($params['name'], 'general_date');
		$this->_value = rv($params['value'], '');
		$this->_place_holder = rvs($params['place_holder'], 'Not set...');
		$this->_start_empty = rvb($params['start_empty'], false);
		$this->_start_now = rvb($params['start_now'], false);
		$this->_show_clear_img = rvb($params['show_clear_img'], true);
		$this->_dt_format = rv($params['dt_format'], 'd-m-Y');
		$this->_class = rv($params['class'], '');
		$this->_input_width = rv($params['input_width'], 110);
		$this->_now = date($this->_dt_format);
		$this->_hidden =  rv($params['hidden'], false);
		if ($this->_hidden){$this->_display = 'none';}else{$this->_display = 'inline-flex';}
		$this->_date = $this->build_date();
	}


	public function build_date(){
		if (empty($this->_value) || $this->_value == date($this->_dt_format, strtotime(''))){
			if ($this->_start_now){$this->_value = now($this->_dt_format);}
			if ($this->_start_empty){$this->_value = '';}
		}else{
			$this->_value = date($this->_dt_format, strtotime($this->_value));
		}

		$tmp = '<script>'.PHP_EOL;
		$tmp .= '$( function() {'.PHP_EOL;
		if ($this->_show_clear_img){
			$tmp .= "$('.clear-date').on('click',function(){".PHP_EOL;
			$tmp .= "var d_id = $(this).attr('id');".PHP_EOL;
			$tmp .= "d_id = d_id.substring(3);".PHP_EOL;
			$tmp .= "$('#'+d_id).val('');".PHP_EOL;
			$tmp .= "});".PHP_EOL;
		}

		$tmp .= '$("#'.$this->_name.'").AnyTime_noPicker().AnyTime_picker({'.PHP_EOL;

		$js_date_fmt = $this->_php_to_js_dt_format($this->_dt_format);
		$tmp .= 'format:"'.$js_date_fmt.'",'.PHP_EOL;
		$tmp .= '});';
		$tmp .= '});</script>';
		if (!empty($this->_class)){$this->_class = ' '.$this->_class;}
		$tmp .= "<div class = 'ifc'><input type = 'text' id = '".$this->_name."' name = '".$this->_name."' value = '".$this->_value."' class = 'point dt_input".$this->_class."' placeholder = '".$this->_place_holder."' style = 'width:".$this->_input_width."px;' readonly />";

		if ($this->_show_clear_img){
			if (file_exists(__s_app_icon_folder__.'20/clear20.png')){
				$clear_ico = __s_app_icon_url__.'20/clear20.png';
			}else{
				$clear_ico = __s_lib_icon_url__.'20/clear20.png';
			}
			$tmp .= "&nbsp;<img class = 'ttip clear-date' id = 'cd_".$this->_name."' src = '".$clear_ico."' title = 'Clear the date.' alt = 'Clear' />";
		}
		$tmp .= '</div>';
		return $tmp;
	}

	private function _php_to_js_dt_format($dt_format){
		if (empty($dt_format)){return '';}
		switch ($dt_format){
			case 'd-m-Y':
				$fmt = '%d-%m-%Y';
				break;
			case 'H:i':
				$fmt = '%H:%i';
				break;
			case 'H:i:s':
				$fmt = '%H:%i:%s';
				break;
			case 'd-m-Y H:i':
				$fmt = '%d-%m-%Y %H:%i';
				break;
			case 'd-m-Y H:i:s':
				$fmt = '%d-%m-%Y %H:%i:%s';
				break;
			default:
				$fmt = '';
		}
		return $fmt;
	}



	public function _set_name($_name) {$this->_name = $_name;}
	public function _set_input_width($t) {$this->_input_width = $t;}
	public function _set_value($_value) {$this->_value = $_value;}
	public function _set_place_holder($_place_holder) {$this->_place_holder = $_place_holder;}
	public function _set_show_clear_img($_show_clear_img) {$this->_show_clear_img = $_show_clear_img;}
	public function _set_dt_format($_dt_format) {$this->_dt_format = $_dt_format;}
	public function _set_class($_class) {$this->_class = $_class;}


	public function _get_name() {return $this->_name;}
	public function _get_value() {return $this->_value;}
	public function _get_place_holder() {return $this->_place_holder;}
	public function _get_startEmpty() {return $this->_start_empty;}
	public function _get_start_now() {return $this->_start_now;}
	public function _get_show_clear_img() {return $this->_show_clear_img;}
	public function _get_dt_format() {return $this->_dt_format;}
	public function _get_class() {return $this->_class;}
	public function _get_date() {return $this->_date;}

}//end of _datetime class

