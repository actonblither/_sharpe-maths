<?php
class _form_element {
	private $_dbh;
	private $_db_tbl;
	private $_el_display_field;
	private $_el_fieldset_id;
	private $_el_field_id;
	private $_el_field_value;
	private $_el_field_class;
	private $_el_place_holder;
	private $_el_pattern;
	private $_el_display;
	private $_el_hidden;
	private $_el_return;
	private $_el_width;
	private $_el_height;
	private $_el_width_units = 'px';
	private $_el_height_units = 'px';

	private $_timer_dt;

	private $_dt_start_empty;
	private $_dt_start_now;
	private $_dt_show_clear_img;
	private $_dt_format;
	private $_dt_now ;
	private $_form_id_prefix;
	private $_list_id_prefix;



	public function __construct($_params = []){
		$this->_dbh = new _db();
		if (!empty($_params)){
			$this->_db_tbl = rvs($_params['db_tbl']);
			$this->_el_display_field = rvs($_params['el_display_field']);
			$this->_el_field_id = rvs($_params['el_field_id']);
			$this->_el_fieldset_id = rvs($_params['el_fieldset_id']);
			$this->_el_field_value = rv($_params['el_field_value']);
			$this->_el_pattern = rv($_params['el_pattern']);
			$this->_el_hidden = rvb($_params['el_hidden']);
			$this->_el_place_holder = rvs($_params['el_place_holder'], 'Not set...');
			$this->_el_style_class = rvs($_params['el_style_class']);
			$this->_el_width = rvs($_params['el_width'], '400');
			$this->_el_height = rvs($_params['el_height'], '200');
			$this->_el_width_units = rvs($_params['el_width_units'], 'px');
			$this->_el_height_units = rvs($_params['el_height_units'], 'px');
			$this->_el_return = rvs($_params['el_return'], 'ret');
			$this->_el_timer_dt = rvs($_params['el_timer_dt']);

			$this->_form_id_prefix = rvs($_params['form_id_prefix']);
			$this->_list_id_prefix = rvs($_params['list_id_prefix']);

			//date specific parameters
			$this->_dt_start_empty = rvb($_params['dt_start_empty'], false);
			$this->_dt_start_now = rvb($_params['dt_start_now'], false);
			$this->_dt_show_clear_img = rvb($_params['dt_show_clear_img'], true);
			$this->_dt_format = rv($_params['dt_format'], 'd-m-Y');
			$this->_dt_now = date($this->_dt_format);
			$this->_dt_hidden =  rv($_params['dt_hidden'], false);
			if ($this->_dt_hidden){$this->_dt_display = 'none';}else{$this->_dt_display = 'inline-flex';}
		}
	}

	public function _build_text_input(){
		if (empty($this->_el_width)){$this->_el_width = '400';}
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);

		$maxlength = $this->_fetch_varchar_field_length($this->_db_tbl, $this->_el_field_id);

		$tmp =  "<input type = 'text' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' value = '".$this->_el_field_value."' ";
		if (!empty($this->_el_pattern)){$tmp.= "pattern = '".$this->_el_pattern."' ";}
		$tmp .= "maxlength = '".$maxlength."' class = 'field' style = 'width:".$this->_el_width.$this->_el_width_units.";' />";
		return $tmp;
	}

	public function _build_read_only_text_input(){
		if (empty($this->_el_width)){$this->_el_width = 400;}
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);
		$maxlength = $this->_fetch_varchar_field_length($this->_db_tbl, $this->_el_field_id);
		return "<input readonly type = 'text' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' value = '".$this->_el_field_value."' maxlength = '".$maxlength."' class = 'field blend' style = 'width:".$this->_el_width.$this->_el_width_units.";' />";
	}

	public function _build_password_input(){
		return "<input type = 'password' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' value = '' class = 'field' style = 'width:".$this->_el_width.$this->_el_width_units.";' />";
	}

	public function _build_checkbox(){
		if ($this->_el_hidden){ $style = " style = 'display: none;' ";}else{$style = '';}
		$tmp = '<input type = "checkbox" name = "'.$this->_el_field_id.'" id = "'.$this->_el_field_id.'" class = "field point" '.$style;
		$tmp.= frmchecked($this->_el_field_value);
		$tmp.= ' />';
		return $tmp;
	}

	public function _build_textarea(){
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);
		$tmp = "<textarea id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' class = 'field' style = 'width:".$this->_el_width.$this->_el_width_units."; height:".$this->_el_height.$this->_el_height_units.";'>";
		$tmp .= $this->_el_field_value;
		$tmp .= '</textarea>';
		return $tmp;
	}

	public function _build_hidden_input(){
		return "<input type = 'hidden' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' value = '".$this->_el_field_value."' class = 'field' />";
	}

	public function _build_file_picker(){
		return "<input type = 'file' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' class = 'field' />";
	}

	public function _build_ckeditor(){
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);
		$tmp = "<div class = 'ck-dummy'><textarea id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' class = '".$this->_el_field_class."'>".PHP_EOL;
		$tmp.= $this->_el_field_value;
		$tmp .= '</textarea>';
		$tmp .= "<script>
			CKEDITOR.replace('".$this->_el_field_id."',{
				language: 'en',
				width: '".$this->_el_width.$this->_el_width_units."',
				height: '".$this->_el_height.$this->_el_height_units."'
			});
			</script></div>";
		return $tmp;
	}

	public function _build_timer_element(){
		$tmp = "
			<script>
				function updatedisplay(watch) {
					$('#".$this->_el_field_id."').val(watch.toString());
				}
				var timer_obj = new Stopwatch(updatedisplay, 50);
				$(document).ready(function () {
					function set_date_time(){
						var d = new Date();
						$('#".$this->_form_id_prefix.$this->_el_timer_dt."').val(moment().format('DD-MM-YYYY H:m:s'));
					}
					$(document).on('click', '#".$this->_list_id_prefix."_start_btn', function(){
						timer_obj.start();
						set_date_time();
						$('#".$this->_list_id_prefix."_start_btn').addClass('hidden');
						$('#".$this->_list_id_prefix."_stop_btn').removeClass('hidden');
					});
					$(document).on('click', '#".$this->_list_id_prefix."_stop_btn', function(){
						timer_obj.stop();
						$('#".$this->_list_id_prefix."_start_btn').removeClass('hidden');
						$('#".$this->_list_id_prefix."_stop_btn').addClass('hidden');
						$('#".$this->_list_id_prefix."_ftlsave').removeClass('hidden');
					});
				});
			</script>";
		$tmp.= "<div class = 'f-row'><input type = 'text' class = 'date-input field' name = '";
		$tmp.= $this->_el_field_id."' id = '".$this->_el_field_id."' value = '00:00:00' readonly ";
		$tmp.= "onKeyPress = 'return disableEnterKey(event)' style = 'width:".$this->_el_width.$this->_el_width_units.";' />";
		$tmp.= "<button type = 'button' class = 'button time' id = '".$this->_list_id_prefix."_start_btn' name = '".$this->_list_id_prefix."_start_btn'>Start</button>";
		$tmp.= "<button class = 'button time hidden' id = '".$this->_list_id_prefix."_stop_btn' type = 'button'>Stop</button></div>";
		return $tmp;
	}



	public function _build_date(){
		if (empty($this->_el_field_value) || $this->_el_field_value == date($this->_dt_format, strtotime(''))){
			if ($this->_dt_start_now){$this->_el_field_value = now($this->_dt_format);}
			if ($this->_dt_start_empty){$this->_el_field_value = '';}
		}else{
			$this->_el_field_value = date($this->_dt_format, strtotime($this->_el_field_value));
		}

		$tmp = '<script>'.PHP_EOL;
		$tmp .= '$( function() {'.PHP_EOL;
		if ($this->_dt_show_clear_img){
			$tmp .= "$('.clear-date').on('click',function(){".PHP_EOL;
			$tmp .= "var d_id = $(this).attr('id');".PHP_EOL;
			$tmp .= "d_id = d_id.substring(3);".PHP_EOL;
			$tmp .= "$('#' + d_id).val('');".PHP_EOL;
			$tmp .= "});".PHP_EOL;
		}

		$tmp .= '$("#'.$this->_el_field_id.'").AnyTime_noPicker().AnyTime_picker({'.PHP_EOL;

		$js_date_fmt = $this->_php_to_js_dt_format($this->_dt_format);
		$tmp .= 'format:"'.$js_date_fmt.'",'.PHP_EOL;
		$tmp .= '});';
		$tmp .= '});</script>';
		$tmp .= "<div class = 'inline-center'><input type = 'text' id = '".$this->_el_field_id."' name = '".$this->_el_field_id."' value = '".$this->_el_field_value."' class = 'point dt_input ".$this->_el_style_class."' placeholder = '".$this->_el_place_holder."' style = 'width:".$this->_el_width.$this->_el_width_units.";' readonly />";

		if ($this->_dt_show_clear_img){
			if (file_exists(__s_app_icon_folder__.'/20/clear20.png')){
				$clear_ico = __s_app_icon_url__.'/20/clear20.png';
			}else{
				$clear_ico = __s_icon_url__.'/20/clear20.png';
			}
			$tmp .= "<img class = 'ttip clear-date' id = 'cd_".$this->_el_field_id."' src = '".$clear_ico."' title = 'Clear the date.' alt = 'Clear' />";
		}
		$tmp .= '</div>';
		if ($this->_el_return == 'echo'){
			echo $tmp;
		}else{
			return $tmp;
		}
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

	public function _build_radio_input_set(){
		$sql = 'select id, '.$this->_el_display_field.' from '.$this->_db_tbl.' where display = 1 and archived = 0 order by order_num';
		$rows = $this->_dbh->_fetch_db_rows($sql);
		if (!empty($rows)){
			$tmp = '<fieldset class = "radio-group" id = "'.$this->_el_fieldset_id.'">';
			foreach ($rows as $r){
				$tmp .= '<label class = "radioset"><input type = "radio" name = "'.$this->_el_field_id.'" value = "'.$r['id'].'" ';
				if ($this->_el_field_value == $r['id']){$tmp .= "checked ";}
				$tmp .= '/> '.$r[$this->_el_display_field].'</label>'.PHP_EOL;
			}
			$tmp .= '</fieldset>';
		}
		if ($this->_el_return == 'echo'){
			echo $tmp;
		}else{

			return $tmp;
		}
	}

	public function _fetch_varchar_field_length(){
		$sql = 'show columns from '.$this->_db_tbl.' where Field = "'.$this->_el_field_id.'"';
		$row = @$this->_dbh->_fetch_db_row($sql);
		if (instr('varchar', $row['Type'])){
			$length = str_replace('varchar','', $row['Type']);
			$length = str_replace('(','', $length);
			$length = str_replace(')','', $length);
			return $length;
		}else{
			return false;
		}
	}

	public function _set_el_field_value($t) {$this->_el_field_value = $t;}
	public function _set_el_field_id($t) {$this->_el_field_id = $t;}
	public function _set_el_field_class($t) {$this->_el_field_class = $t;}
	public function _set_el_width($t) {$this->_el_width = $t;}
	public function _set_el_height($t) {$this->_el_height = $t;}
	public function _set_el_width_units($t) {$this->_el_width_units = $t;}
	public function _set_el_height_units($t) {$this->_el_height_units = $t;}
}

class _parent_child_select_chain{
	private $_jq;
	private $_dbh;
	private $_parent_id_select;
	private $_child_id_select;
	private $_parent_text_field;
	private $_child_text_field;

	public function __construct($params){
		$this->_dbh = new _db();

		if (!is_array($params['my_child_vals'])){
			$params['my_child_vals'] = (array) $params['my_child_vals'];
		}
		$_my_child_vals_arr = rvaz($params['my_child_vals']);
		$_my_child_vals_str = implode(',', $_my_child_vals_arr);

		if (!is_array($params['my_parent_vals'])){
			$params['my_parent_vals'] = (array) $params['my_parent_vals'];
		}
		$_my_parent_vals_arr = rvaz($params['my_parent_vals']);
		$_my_parent_vals_str = implode(',', $_my_parent_vals_arr);

		if (empty($_my_child_vals_str)){
			$_my_child_vals_str = '0';
		}
		if (empty($_my_parent_vals_str)){
			$_my_parent_vals_str = '0';
		}
		$_parent_table = rvs($params['parent_table']);
		$_child_table = rvs($params['child_table']);
		$_link_table = rvs($params['link_table']);
		$_parent_sel_id = rvs($params['parent_sel_id']);
		$_child_sel_id = rvs($params['child_sel_id']);
		$_parent_db_sel_id = rvs($params['parent_db_sel_id']);
		$_child_db_sel_id = rvs($params['child_db_sel_id']);
		$_parent_text_field = rvs($params['parent_text_field'], 'title');
		$_child_text_field = rvs($params['child_text_field'], 'title');


		$_multiple = rvb($params['multiple']);

		if (empty($_parent_db_sel_id)){$_parent_db_sel_id=$_parent_sel_id;}
		if (empty($_child_db_sel_id)){$_child_db_sel_id=$_child_sel_id;}


		$_height = rvz($params['height']);
		$_placeholder = rvs($params['placeholder']);

		$this->_jq = '
			<script>
				$(document).ready(function(){
					$(document).on("change","#'.$_parent_sel_id.'", function(){
						var sel = $("select#'.$_parent_sel_id.'").val();
						$.ajax({
							type: "POST",
							url: "'.__s_app_url__.'/_stdlib_ajax/_parent_child_chain_select.php",
							data: {
								"parent_values" 	: sel,
								"my_parent_str" 	: "'.$_my_parent_vals_str.'",
								"my_child_str" 	: "'.$_my_child_vals_str.'",
								"parent_table" 	: "'.$_parent_table.'",
								"child_table" 		: "'.$_child_table.'",
								"parent_sel_id" 	: "'.$_parent_sel_id.'",
								"child_sel_id" 	: "'.$_child_sel_id.'",
								"parent_db_sel_id" 	: "'.$_parent_db_sel_id.'",
								"child_db_sel_id" 	: "'.$_child_db_sel_id.'",
								"link_table" 		: "'.$_link_table.'",
								"placeholder"		: "'.$_placeholder.'",
								"multiple"			: "'.$_multiple.'",
								"parent_text_field" 	: "'.$_parent_text_field.'",
								"child_text_field" 	: "'.$_child_text_field.'",
								"app_folder"		:	"'.base64_encode(__s_app_folder__).'"
							},
							beforeSend :function(){
								$("#ajax-loader").show();
							},
							complete : function(){
								$("#ajax-loader").hide();
							},
							success: function(data){
								$("#'.$_child_sel_id.' option:gt(0)").remove();
								$("#'.$_child_sel_id.'").html(data);
							}
						});
					});
				});
			</script>';

		$sql = 'select id,'.$_parent_text_field.' from '.$_parent_table.' where '.$_parent_text_field.' <> "" and display=1 and archived=0 order by '.$_parent_text_field;

		$_parents = $this->_dbh->_fetch_db_rows($sql);
		$_parent_name = array();
		$_parent_id = array();
		if (empty($_parents)===false){
			foreach ($_parents as $p){
				$_parent_name[$p['id']] = $p[$_parent_text_field];
			}
		}
		$_parent_name_str = implode(',', $_parent_name);
		if (!empty($_my_parent_vals_arr)){
			if (!empty($_link_table)){
				$count = 0;
				$result = array();
				if (!empty($_my_parent_vals_arr)){
					foreach ($_my_parent_vals_arr as $par){
						$sql = 'select distinct j.id, j.'.$_child_text_field.' from '.$_child_table.' j left join '.$_link_table.' jl on j.id = jl.'.$_child_db_sel_id.' where jl.'.$_parent_db_sel_id.' = '.$par.'  and j.display=1 and j.archived=0 order by j.'.$_parent_text_field;
						if (is_int($par)){
							$result[$par] = $this->_dbh->_fetch_db_rows($sql);
						}
					}
				}
			}else{
				$count = 0;
				$result = array();
				if (!empty($_my_parent_vals_arr)){
					foreach ($_my_parent_vals_arr as $par){
						$sql = 'select distinct id,'.$_child_text_field.' from '.$_child_table.' where '.$_parent_db_sel_id. ' = '.$par.' and display=1 and archived=0 order by '.$_child_text_field;

						if (is_int($par)){
							$result[$par] = $this->_dbh->_fetch_db_rows($sql);
						}
					}
				}
			}
			$_children = $result;
		}

		// Start parent select create
		$this->_parent_id_select = "
			<select class = 'field' id = '".$_parent_sel_id."' name = '".$_parent_sel_id;
		if ($_multiple==1 || $_multiple==true){
			$this->_parent_id_select .= "[]' multiple ";
		}else{
			$this->_parent_id_select .= "' ";
		}
		if ($_height>0){
			$this->_parent_id_select .= " style = 'height: ".$_height."px'";
		}
		$this->_parent_id_select .= ">";
		if (!empty($_placeholder)){
			$this->_parent_id_select .= "<option value = '' class = 'not-set'>".$_placeholder."</option>".PHP_EOL;
		}
		if (!empty($_parents)){
			foreach($_parents as $c){
				$this->_parent_id_select .= "<option value = '".$c['id']."' ";
				if (in_array($c['id'], $_my_parent_vals_arr)){
					$this->_parent_id_select.= "selected = 'selected'";
				}
				$this->_parent_id_select.= '>'.$c[$_parent_text_field].'</option>';
			}
		}
		$this->_parent_id_select .= '</select>';

		//End of Parent select code


		// Start of child select code

		$this->_child_id_select= "
			<select class = 'field' id = '".$_child_sel_id."' name = '".$_child_sel_id;
		if ($_multiple == 1 || $_multiple == true){
			$this->_child_id_select .= "[]' multiple ";
		}else{
			$this->_child_id_select .= "' ";
		}
		if ($_height>0){
			$this->_child_id_select .= ' style = "height: '.$_height.'px"';
		}
		$this->_child_id_select .= ">";

		$tmp = '';
		if (!empty($_placeholder)){
			$tmp .= "<option class = 'not-set' value = ''>$_placeholder</option>".PHP_EOL;
		}
		if (!empty($_children)){
			foreach($_children as $key=>$val_arr){
				$parent_id = '';
				if ($_multiple == 1 || $_multiple == true){
					$tmp .= "<option disabled = 'disabled' value = ''>".$_parent_name[$key]."</option>".PHP_EOL;
					$parent_id = $key.'_';
				}
				if ($val_arr!=false){
					foreach ($val_arr as $val){
						$tmp .= "<option value = '".$parent_id.$val['id']."' ";
						if (in_array($val['id'], $_my_child_vals_arr)){ $tmp .= " selected = 'selected' ";}
						$tmp.= ">".$val[$_child_text_field]."</option>";
					}
				}
			}
		}
		$this->_child_id_select.=$tmp.'</select>';
		//End of child select code
	}

	public function _get_parent_id_select(){return $this->_parent_id_select;}
	public function _get_child_id_select(){return $this->_child_id_select;}
	public function _get_parent_child_jquery(){return $this->_jq;}

}//end class

// Requires access to the _datetime() class.

class _select{

	private $_dbh;

	private $_db_display_field;
	private $_db_sql;
	private $_db_table;

	private $_el_classes;// array containing style classes
	private $_el_label;
	private $_el_opt_val;
	private $_el_name;//This will be both name and id
	private $_el_number_inc;
	private $_el_number_min;
	private $_el_number_max;
	private $_el_value;//the value to be selected in the element
	private $_el_style;//array containing style overrides
	private $_el_type;// string taking values 'enum', 'number', 'yn','yn+sel','sel'
	private $_el_width;// in pixels
	private $_el_yn_preselect; // boolean if true sel only appears once a y/n preselect is set to 1
	private $_el_yn_value;// the value of the yes/no select box (1 or 0)
	private $_yes;
	private $_no;
	private $_el_date_name;
	private $_el_date_value;

	public function __construct($params = []) {
		$this->_dbh = new _db();
		if (!empty($params)){
			$this->_db_display_field = rvs($params['db_display_field']);
			$this->_db_sql = rvs($params['db_sql']);
			$this->_db_table = rvs($params['db_table']);

			$this->_el_classes = rvs($params['el_classes']);
			$this->_el_label = rv($params['el_label']);
			$this->_el_name = rvs($params['el_name']);
			$this->_el_number_inc = rvs($params['el_number_inc']);
			$this->_el_number_min = rvs($params['el_number_min']);
			$this->_el_number_max = rvs($params['el_number_max']);

			$this->_el_value = rv($params['el_value'],'');
			$this->_el_style = rvs($params['el_style']);
			$this->_el_type = rvs($params['el_type'], 'sel');
			$this->_el_width = rvz($params['el_width'],200);
			$this->_el_yn_preselect = rvb($params['el_yn_preselect'], false);
			$this->_el_yn_value = rvz($params['el_yn_value'],0);
			$this->_yes = rv($params['yes'], 'y');
			$this->_no = rv($params['no'], 'n');
		}
	}

	public function _build_select(){
		$tmp = '';
		if ($this->_el_type === 'yn+sel'){
			$tmp .= $this->_build_yn_select();
			$tmp .= $this->_build_select_element();
		}else if ($this->_el_type === 'sel'){
			$tmp .= $this->_build_select_element();
		}else if ($this->_el_type === 'number'){
			$tmp .= $this->_build_number_select();
		}else if ($this->_el_type === 'enum'){
			$tmp .= $this->_build_enum_select();
		}else if ($this->_el_type === 'yn'){
			$tmp.=$this->_build_yn_select();
		}elseif ($this->_el_type === 'yn+date'){
			$tmp .= $this->_build_yn_date();
			$tmp .= $this->_build_date();
		}
		return $tmp;
	}

	private function _build_select_element(){
		if ($this->_el_type == 'yn+sel'){
			$this->_el_name .= '_id';
			if ($this->_el_yn_value == 0) {$hidden = ' hidden';}else{$hidden = '';}
		}else if ($this->_el_type === 'yn+date'){
			if ($this->_el_yn_value == 0) {$hidden = ' hidden';}else{$hidden = '';}
		}else{
			$hidden = '';
		}
		if (!empty($this->_el_label)){
			$tmp = "<div class = 'ml20 mr10 b'>$this->_el_label: </div>";
		}else{
			$tmp = '';
		}
		$tmp .= '<select name = "'.$this->_el_name.'" id = "'.$this->_el_name.'" class = "field'.$hidden.'" style = "width:'.$this->_el_width.'px;">';
		$tmp .= '<option value = 0 class = "not-set">Not set...</option>';
		if (empty($this->_db_sql)){
			$this->_db_sql = 'select id,'.$this->_db_display_field.' from '.$this->_db_table.' where display=1 and archived=0 order by order_num,'.$this->_db_display_field;
		}
		$rows = $this->_dbh->_fetch_db_rows($this->_db_sql);
		if (empty($this->_el_opt_val)){$this->_el_opt_val = 'id';}
		if (!empty($rows)){
			foreach ($rows as $row){
				if (!is_int($row[$this->_el_opt_val]) && !is_null($row[$this->_el_opt_val])){
					$row[$this->_el_opt_val] = "'".$row[$this->_el_opt_val]."'";
				}
				$tmp .= '<option value = '.$row[$this->_el_opt_val].'';
				if ($row['id'] == $this->_el_value){$tmp .= ' selected = "selected"';}
				$tmp .= '>';
				$tmp .= $row[$this->_db_display_field];
				$tmp .= '</option>'.PHP_EOL;
			}
		}
		$tmp .= '</select>';
		return $tmp;
	}

	private function _build_yn_date(){
		$tmp = '<script>$(document).ready(function(){';
		$tmp .= '$(document).on("change","#'.$this->_el_name.'",function(e){';
		$tmp .= 'e.preventDefault();e.stopImmediatePropagation();';
		$tmp .= 'var yn_val = $(this).val();';
		$tmp .= 'if (yn_val == "'.$this->_yes.'"){';
		$tmp .= '$("#'.$this->_el_date_name.'").removeClass("hidden");';
		$tmp .= '$("#cd_'.$this->_el_date_name.'").removeClass("hidden");';
		$tmp .= '}else{';
		$tmp .= '$("#'.$this->_el_date_name.'").addClass("hidden");';
		$tmp .= '$("#cd_'.$this->_el_date_name.'").addClass("hidden");';
		$tmp .= '}';
		$tmp .= '});';
		$tmp .= '});</script>';
		$tmp .= '<select id = "'.$this->_el_name.'" name = "'.$this->_el_name.'" class = "field">';
		$tmp .= '<option value = "" class = "not-set">Not set...</option>';
		$tmp .= '<option value = "'.$this->_yes.'"';
		if ($this->_el_yn_value==$this->_yes){$tmp .= ' selected = "selected"';}
		$tmp .= '>Yes</option>';
		$tmp .= '<option value = "'.$this->_no.'"';
		if ($this->_el_yn_value==$this->_no){$tmp .= ' selected = "selected"';}
		$tmp .= '>No</option>';
		$tmp .= '</select>';
		return $tmp;
	}

	private function _build_yn_select(){
		$tmp = '<script>$(document).ready(function(){';
		$tmp .= '$(document).on("change","#'.$this->_el_name.'",function(e){';
		$tmp .= 'e.preventDefault();e.stopImmediatePropagation();';
		$tmp .= 'var yn_val = $(this).val();';
		$tmp .= 'if (yn_val == "'.$this->_yes.'"){';
		$tmp .= '$("#'.$this->_el_name.'_id").removeClass("hidden");';
		$tmp .= '}else{';
		$tmp .= '$("#'.$this->_el_name.'_id").addClass("hidden");';
		$tmp .= '}';
		$tmp .= '});';
		$tmp .= '});</script>';
		$tmp .= '<select id = "'.$this->_el_name.'" name = "'.$this->_el_name.'" class = "field">';
		$tmp .= '<option value = "" class = "not-set">Not set...</option>';
		$tmp .= '<option value = "'.$this->_yes.'"';
		if ($this->_el_yn_value==$this->_yes){$tmp .= ' selected = "selected"';}
		$tmp .= '>Yes</option>';
		$tmp .= '<option value = "'.$this->_no.'"';
		if ($this->_el_yn_value==$this->_no){$tmp .= ' selected = "selected"';}
		$tmp .= '>No</option>';
		$tmp .= '</select>';
		return $tmp;
	}

	private function _build_enum_select(){
		$tmp = '<select name = "'.$this->_el_name.'" id = "'.$this->_el_name.'" class = "field">';
		$tmp .= '<option value = "" class = "not-set">Not set...</option>';
		$sql = "show fields from ".$this->_db_table." where field = '".$this->_el_name."'";
		$row = $this->_dbh->_fetch_db_row($sql);
		$str = $row['Type'];
		$str = str_replace('enum(', '', $str);
		$str = str_replace(')', '', $str);
		$ee = explode(',', $str);

		$enum_array = array();
		foreach ($ee as $e){
			$enum_array[] = str_replace("'", '', $e);
		}

		foreach ($enum_array as $row){
			if (!empty($row)){
				$tmp .= '<option value = "'.$row.'"';
				if ($row == $this->_el_value){$tmp .= ' selected = "selected"';}
				$tmp .= '>';
				$tmp.=ucfirst($row);
				$tmp .= '</option>'.PHP_EOL;
			}
		}
		$tmp .= '</select>';
		return $tmp;
	}

	private function _build_number_select(){
		$tmp = '<select id = "'.$this->_el_name.'" name = "'.$this->_el_name.'" class = "field">';
		$tmp .= '<option value = "" class = "not-set">Not set...</option>';
		for ($i = $this->_el_number_min; $i <= $this->_el_number_max; $i++){
			$value = $i*$this->_el_number_inc;
			$tmp .= '<option value = "'.$value.'" ';
			if ($value == $this->_el_value){$tmp .= 'selected = "selected"';}
			$tmp .= '>'.$value.'</option>';
		}
		$tmp .= '</select>';
		return $tmp;
	}



	private function _build_date(){
		$_params = array(
			'el_field_id' => $this->_el_date_name,
			'el_field_value' => $this->_el_date_value,
			'el_place_holder' => 'Not set...',
			'dt_start_empty' => false,
			'dt_start_now' => true,
			'dt_show_clear_img' => true,
			'dt_format' => 'd-m-Y',
			'el_width' => 110
		);

		$ds = new _form_element($_params);
		return $ds->_build_date();
	}

	// SETTERS
	public function _set_db_display_field($t) {$this->_db_display_field = $t;}
	public function _set_db_sql($t) {$this->_db_sql = $t;}
	public function _set_db_table($t) {$this->_db_table = $t;}

	public function _set_el_classes($t) {$this->_el_classes = $t;}
	public function _set_el_label($t) {$this->_el_label = $t;}
	public function _set_el_opt_val($t) {$this->_el_opt_val = $t;}
	public function _set_el_name($t) {$this->_el_name = $t;}
	public function _set_el_number_min($t) {$this->_el_number_min = $t;}
	public function _set_el_number_max($t) {$this->_el_number_max = $t;}
	public function _set_el_number_inc($t) {$this->_el_number_inc = $t;}
	public function _set_el_value($t) {$this->_el_value = $t;}
	public function _set_el_field_value($t) {$this->_el_field_value = $t;}
	public function _set_el_field_id($t) {$this->_el_field_id = $t;}
	public function _set_el_style($t) {$this->_el_style = $t;}
	public function _set_el_width($t) {$this->_el_width = $t;}
	public function _set_el_yn_preselect($t) {$this->_el_yn_preselect = $t;}
	public function _set_el_yn_value($t) {$this->_el_yn_value = $t;}
	public function _set_yes($t){$this->_yes = $t;}
	public function _set_no($t){$this->_no = $t;}
	public function _set_el_date_name($t) {$this->_el_date_name = $t;}
	public function _set_el_date_value($t) {$this->_el_date_value = $t;}
	public function _set_el_type($t) { $this->_el_type = $t; }
	public function _get_el_value(){return $this->_el_value;}

}
?>