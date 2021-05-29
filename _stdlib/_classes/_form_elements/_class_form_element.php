<?php
class _form_element {
	private $_dbh;
	private $_db_tbl;
	private $_el_display_field;
	private $_el_data_id;
	private $_el_fieldset_id;
	private $_el_id_value;
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
	private $_parent_path;
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
			$this->_el_id_value = rvs($_params['el_id_value']);
			$this->_el_display_field = rvs($_params['el_display_field']);
			$this->_el_field_id = rvs($_params['el_field_id']);
			$this->_el_fieldset_id = rvs($_params['el_fieldset_id']);
			$this->_el_field_value = rv($_params['el_field_value']);
			$this->_el_pattern = rv($_params['el_pattern']);
			$this->_el_hidden = rvb($_params['el_hidden']);
			$this->_el_place_holder = rvs($_params['el_place_holder'], 'Not set...');
			$this->_el_style_class = rvs($_params['el_style_class']);
			$this->_el_width = rvs($_params['el_width'], '250');
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

	public function _delete_jq_code(){
		if (is_logged_in()){
			$tmp = "";
		}
	}

	public function _delete_img_code(){
		if (is_logged_in()){
			$tmp = "<div><img class='w14 h14 point del_qu m5' src = '".__s_lib_url__."_images/_icons/close14.png' /></div>";
		}
	}

	public function _build_save_btn(){
		$tmp = "<button type = 'button' class = 'm10 point page-save save ".$this->_el_field_class."' style = 'width:".$this->_el_width.$this->_el_width_units."' data-db-tbl = '".$this->_db_tbl."' data-id = '".$this->_el_id_value."' data-field = '".$this->_el_field_id."'>".$this->_el_field_value."</button>";
		return $tmp;
	}

	public function _build_text_input(){
		if (empty($this->_el_width)){$this->_el_width = '250';}
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);

		$maxlength = $this->_fetch_varchar_field_length($this->_db_tbl, $this->_el_field_id);

		$tmp =  "<input type = 'text' id = '".$this->_el_field_id."_".$this->_el_id_value."' name = '".$this->_el_field_id."' style='width: ".$this->_el_width.$this->_el_width_units."' value = '".$this->_el_field_value."' ";
		if (!empty($this->_el_pattern)){$tmp.= "pattern = '".$this->_el_pattern."' ";}
		$tmp .= "maxlength = '".$maxlength."' placeholder = '".$this->_el_place_holder."' data-el-type = 'varchar' data-id = '".$this->_el_id_value."' data-db-tbl = '".$this->_db_tbl."' data-field = '".$this->_el_field_id."' class = 'field' />";
		return $tmp;
	}


	public function _build_textarea(){
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);
		$tmp = "<textarea id = '".$this->_el_field_id."_".$this->_el_id_value."' data-el-type = 'textarea' data-field = '".$this->_el_field_id."' data-id = '".$this->_el_id_value."' data-db-tbl = '".$this->_db_tbl."' name = '".$this->_el_field_id."' class = 'field m5'  style = 'width:".$this->_el_width.$this->_el_width_units.";height:".$this->_el_height.$this->_el_height_units."'>";
		$tmp .= $this->_el_field_value;
		$tmp .= '</textarea>';
		return $tmp;

	}

	public function _build_checkbox(){
		if ($this->_el_field_value == 1){$_checked = ' checked';}else{$_checked = '';}
		$tmp = "<input type = 'checkbox' id = '".$this->_el_field_id."_".$this->_el_id_value."' data-el-type = 'checkbox' data-field = '".$this->_el_field_id."' data-id = '".$this->_el_id_value."' data-db-tbl = '".$this->_db_tbl."' name = '".$this->_el_field_id."' class = 'chk-field m5'".$_checked."/>";
		return $tmp;

	}
	public function _build_ckeditor(){
		$tmp = '';
		$this->_el_field_value = str_replace("'","&#39;", $this->_el_field_value);
		if (!empty($this->_el_field_class)){$this->_el_field_class .= ' ';}
		$tmp .= "<div class = 'ck-dummy'><textarea id = '".$this->_el_field_id."_".$this->_el_id_value."' name = '".$this->_el_field_id."' data-id = '".$this->_el_id_value."' data-db-tbl = '".$this->_db_tbl."' data-field = '".$this->_el_field_id."' class = '".$this->_el_field_class."field'>".PHP_EOL;
		$tmp .= $this->_el_field_value;
		$tmp .= '</textarea>';
		$tmp .= "<script>
								var editor = CKEDITOR.replace('".$this->_el_field_id."_".$this->_el_id_value."',{
									language: 'en',
									width: '".$this->_el_width.$this->_el_width_units."',
									height: '600px',
									basicEntities : false,
									entities : false,
									forceSimpleAmpersand : true,
									allowedContent : true,

									on: {

										key: function(evt) {
//console.log(evt.data.keyCode);
											if (evt.data.keyCode === 112 || evt.data.keyCode === 113 || evt.data.keyCode === 114 || evt.data.keyCode === 27 || evt.data.keyCode === 1114195){
												for (var i in CKEDITOR.instances) {
													CKEDITOR.instances[i].updateElement();
												}
												var cke = [];
												cke['tbl'] = this.element.$.attributes[3].value;
												cke['field'] = this.element.$.attributes[1].value;
												cke['id'] = this.element.$.attributes[2].value;
												cke['val'] = this.getData();
												_save_CKE_field(cke);
											}
										}
									}
								});



			</script></div>";
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



	public function _fetch_varchar_field_length(){
		$sql = 'show columns from '.$this->_db_tbl.' where Field = "'.$this->_el_field_id.'"';
		$row = @$this->_dbh->_fetch_db_row($sql);

		if ($row && instr('varchar', $row['Type'])){
			$length = str_replace('varchar','', $row['Type']);
			$length = str_replace('(','', $length);
			$length = str_replace(')','', $length);
			return $length;
		}else{
			return false;
		}
	}

	public function _get_dbh() { return $this->_dbh; }
	public function _get_db_tbl() { return $this->_db_tbl; }
	public function _get_el_display_field() { return $this->_el_display_field; }
	public function _get_el_data_id() { return $this->_el_data_id; }
	public function _get_el_fieldset_id() { return $this->_el_fieldset_id; }
	public function _get_el_id_value() { return $this->_el_id_value; }
	public function _get_el_field_id() { return $this->_el_field_id; }
	public function _get_el_field_value() { return $this->_el_field_value; }
	public function _get_el_field_class() { return $this->_el_field_class; }
	public function _get_el_place_holder() { return $this->_el_place_holder; }
	public function _get_el_pattern() { return $this->_el_pattern; }
	public function _get_el_display() { return $this->_el_display; }
	public function _get_el_hidden() { return $this->_el_hidden; }
	public function _get_el_return() { return $this->_el_return; }
	public function _get_el_width() { return $this->_el_width; }
	public function _get_el_height() { return $this->_el_height; }
	public function _get_el_width_units() { return $this->_el_width_units; }
	public function _get_el_height_units() { return $this->_el_height_units; }
	public function _get_timer_dt() { return $this->_timer_dt; }
	public function _get_dt_start_empty() { return $this->_dt_start_empty; }
	public function _get_dt_start_now() { return $this->_dt_start_now; }
	public function _get_dt_show_clear_img() { return $this->_dt_show_clear_img; }
	public function _get_dt_format() { return $this->_dt_format; }
	public function _get_dt_now() { return $this->_dt_now; }
	public function _get_form_id_prefix() { return $this->_form_id_prefix; }
	public function _get_list_id_prefix() { return $this->_list_id_prefix; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_db_tbl($_t) { $this->_db_tbl = $_t; }
	public function _set_el_display_field($_t) { $this->_el_display_field = $_t; }
	public function _set_el_data_id($_t) { $this->_el_data_id = $_t; }
	public function _set_el_fieldset_id($_t) { $this->_el_fieldset_id = $_t; }
	public function _set_el_id_value($_t) { $this->_el_id_value = $_t; }
	public function _set_el_field_id($_t) { $this->_el_field_id = $_t; }
	public function _set_el_field_value($_t) { $this->_el_field_value = $_t; }
	public function _set_el_field_class($_t) { $this->_el_field_class = $_t; }
	public function _set_el_place_holder($_t) { $this->_el_place_holder = $_t; }
	public function _set_el_pattern($_t) { $this->_el_pattern = $_t; }
	public function _set_el_display($_t) { $this->_el_display = $_t; }
	public function _set_el_hidden($_t) { $this->_el_hidden = $_t; }
	public function _set_el_return($_t) { $this->_el_return = $_t; }
	public function _set_el_width($_t) { $this->_el_width = $_t; }
	public function _set_el_height($_t) { $this->_el_height = $_t; }
	public function _set_el_width_units($_t) { $this->_el_width_units = $_t; }
	public function _set_el_height_units($_t) { $this->_el_height_units = $_t; }
	public function _set_timer_dt($_t) { $this->_timer_dt = $_t; }
	public function _set_dt_start_empty($_t) { $this->_dt_start_empty = $_t; }
	public function _set_dt_start_now($_t) { $this->_dt_start_now = $_t; }
	public function _set_dt_show_clear_img($_t) { $this->_dt_show_clear_img = $_t; }
	public function _set_dt_format($_t) { $this->_dt_format = $_t; }
	public function _set_dt_now($_t) { $this->_dt_now = $_t; }
	public function _set_form_id_prefix($_t) { $this->_form_id_prefix = $_t; }
	public function _set_list_id_prefix($_t) { $this->_list_id_prefix = $_t; }

}


class _select{

	private $_dbh;
	private $_db_display_field;
	private $_db_sql;
	private $_db_tbl;
	private $_db_link_tbl;
	private $_db_tbl_sel;
	private $_db_link_tbl_field_1;
	private $_db_link_tbl_field_2;


	private $_el_data_db_tbl;
	private $_el_data_id;
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
	private $_top_zero_level = false;
	private $_parent_path;
	private $_use_parent_path;

	public function __construct($params = []) {
		$this->_dbh = new _db();
		if (!empty($params)){
			$this->_db_display_field = rvs($params['db_display_field']);
			$this->_db_sql = rvs($params['db_sql']);
			$this->_db_tbl = rvs($params['db_tbl']);

			$this->_use_parent_path = rvb($params['use_parent_path']);

			$this->_db_tbl = '_app_puzzle';
			$this->_db_tbl_sel = '_app_topic';
			$this->_db_link_tbl = '_app_puzzle_topic_link';
			$this->_db_link_tbl_field_1 = 'topic_id';
			$this->_db_link_tbl_field_2 = 'puzzle_id';

			$this->_el_classes = rvs($params['el_classes']);
			$this->_el_label = rv($params['el_label']);
			$this->_el_name = rvs($params['el_name']);
			$this->_el_number_inc = rvs($params['el_number_inc']);
			$this->_el_number_min = rvs($params['el_number_min']);
			$this->_el_number_max = rvs($params['el_number_max']);

			$this->_el_data_id = rvz($params['el_data_id']);
			$this->_el_data_db_tbl = rvs($params['el_data_db_tbl']);

			$this->_el_value = rv($params['el_value'],'');
			$this->_el_style = rvs($params['el_style']);
			$this->_el_type = rvs($params['el_type'], 'sel');
			$this->_el_width = rvz($params['el_width'], 200);
			$this->_el_yn_preselect = rvb($params['el_yn_preselect'], false);
			$this->_el_yn_value = rvz($params['el_yn_value'],0);
			$this->_yes = rv($params['yes'], 'y');
			$this->_no = rv($params['no'], 'n');
		}
	}

	public function _build_select(){
		$tmp = '';
		if ($this->_el_type === 'multiple'){
			$tmp .= $this->_build_select_multiple();
		}else if($this->_el_type === 'yn+sel'){
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

	private function _build_select_multiple(){
		$_sql = "select id, ".$this->_db_display_field." from ".$this->_db_tbl_sel." where display = :display and archived = :archived";
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		$tmp = "<select data-db-tbl = '".$this->_db_link_tbl."' data-field1 = '".$this->_db_link_tbl_field_1."' data-el-type = 'sel_mult' data-field2 = '".$this->_db_link_tbl_field_2."' data-value2 = '".$this->_el_data_id."' id = 'link_id_".$this->_el_data_id."' data-id = 'link_id_".$this->_el_data_id."' class = 'sel-link-field h300' multiple>";
		if (!empty($_rows)){
			for ($i = 0; $i < count($_rows); $i++){
				if ($_rows[$i]['id'] !== $this->_id){
					$_sql = "select * from ".$this->_db_link_tbl." where (".$this->_db_link_tbl_field_1." = ".$_rows[$i]['id'].") and (".$this->_db_link_tbl_field_2." = ".$this->_el_data_id.")";
					$_sel_row = $this->_dbh->_fetch_db_row($_sql);
					if ($_sel_row[$this->_db_link_tbl_field_1] === $_rows[$i]['id'] && $_sel_row[$this->_db_link_tbl_field_2] === $this->_el_data_id){
						$_sel_text = "selected = 'selected'";
					}else{
						$_sel_text = '';
					}
					$tmp .= "<option value='".$_rows[$i]['id']."' ".$_sel_text.">".$_rows[$i]['title']."</option>";
				}
			}
		}
		$tmp .= "</select>";
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

		$tmp .= "<select data-db-tbl = '".$this->_el_data_db_tbl."' data-field = '".$this->_el_name."' data-id = '".$this->_el_data_id."' id = '".$this->_el_name."_".$this->_el_data_id."' name = '".$this->_el_name."' class = 'sel-field' style='width:".$this->_el_width."px'>";
		if ($this->_top_zero_level){
			$tmp .= "<option value = '0'>Top Level</option>";
		}else{
			$tmp .= "<option value = '0' class = 'not-set'>Not set...</option>";
		}
		if (empty($this->_db_sql)){
			$this->_db_sql = 'select id,'.$this->_db_display_field.' from '.$this->_db_tbl.' where display=1 and archived=0 order by order_num,'.$this->_db_display_field;
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

				if ($this->_use_parent_path == false){
					$tmp .= $row['id']." ".$row[$this->_db_display_field];
				}else{
					//_cl($row['id'], 'ID');
					//_cl($row['parent_id'], 'PARENT ID');
					if (!empty($row['parent_id'])){
						$tmp .= $this->_build_parent_path($row['id'], $row['title']);
					}else{
						$tmp .= $row['id']." ".$row[$this->_db_display_field];
					}
				}
				$tmp .= '</option>'.PHP_EOL;
			}
		}
		$tmp .= '</select>';
		return $tmp;
	}

	private function _build_parent_path($_id, $_title){
		$_pp = "";
		do{
			$_sql = 'select id, parent_id, title from _app_nav_routes where id = :id';
			$_d = array('id' => $_id);
			$_f = array('i');
			$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
			$_id = $_row['parent_id'];
			$_iid = $_row['id'];
			$_pp = $_iid." ".$_row['title']." > ".$_pp;
		}while ($_id != 0);
		$_pp = substr($_pp, 0, -3);
		return $_pp;
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
		$sql = "show fields from ".$this->_db_tbl." where field = '".$this->_el_name."'";
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
		$tmp = "<select data-db-tbl = '".$this->_el_data_db_tbl."' data-field = '".$this->_el_name."' data-id = '".$this->_el_data_id."' id = '".$this->_el_name."_".$this->_el_data_id."' name = '".$this->_el_name."' class = 'sel-field'>";
		$tmp .= "<option value = '' class = 'not-set'>Not set...</option>";
		for ($i = $this->_el_number_min; $i <= $this->_el_number_max; $i++){
			$value = $i * $this->_el_number_inc;
			$tmp .= "<option value = '".$value."' ";
			if ($value == $this->_el_value){$tmp .= 'selected = "selected"';}
			$tmp .= ">".$value."</option>";
		}
		$tmp .= "</select>";
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
	public function _get_dbh() { return $this->_dbh; }
	public function _get_db_display_field() { return $this->_db_display_field; }
	public function _get_db_sql() { return $this->_db_sql; }
	public function _get_db_tbl() { return $this->_db_tbl; }
	public function _get_db_link_tbl() { return $this->_db_link_tbl; }
	public function _get_db_tbl_sel() { return $this->_db_tbl_sel; }
	public function _get_db_link_tbl_field_1() { return $this->_db_link_tbl_field_1; }
	public function _get_db_link_tbl_field_2() { return $this->_db_link_tbl_field_2; }
	public function _get_el_data_db_tbl() { return $this->_el_data_db_tbl; }
	public function _get_el_data_id() { return $this->_el_data_id; }
	public function _get_el_classes() { return $this->_el_classes; }
	public function _get_el_label() { return $this->_el_label; }
	public function _get_el_opt_val() { return $this->_el_opt_val; }
	public function _get_el_name() { return $this->_el_name; }
	public function _get_el_number_inc() { return $this->_el_number_inc; }
	public function _get_el_number_min() { return $this->_el_number_min; }
	public function _get_el_number_max() { return $this->_el_number_max; }
	public function _get_el_value() { return $this->_el_value; }
	public function _get_el_style() { return $this->_el_style; }
	public function _get_el_type() { return $this->_el_type; }
	public function _get_el_width() { return $this->_el_width; }
	public function _get_el_yn_preselect() { return $this->_el_yn_preselect; }
	public function _get_el_yn_value() { return $this->_el_yn_value; }
	public function _get_yes() { return $this->_yes; }
	public function _get_no() { return $this->_no; }
	public function _get_el_date_name() { return $this->_el_date_name; }
	public function _get_el_date_value() { return $this->_el_date_value; }
	public function _get_top_zero_level() { return $this->_top_zero_level; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_db_display_field($_t) { $this->_db_display_field = $_t; }
	public function _set_db_sql($_t) { $this->_db_sql = $_t; }
	public function _set_db_tbl($_t) { $this->_db_tbl = $_t; }
	public function _set_db_link_tbl($_t) { $this->_db_link_tbl = $_t; }
	public function _set_db_tbl_sel($_t) { $this->_db_tbl_sel = $_t; }
	public function _set_db_link_tbl_field_1($_t) { $this->_db_link_tbl_field_1 = $_t; }
	public function _set_db_link_tbl_field_2($_t) { $this->_db_link_tbl_field_2 = $_t; }
	public function _set_el_data_db_tbl($_t) { $this->_el_data_db_tbl = $_t; }
	public function _set_el_data_id($_t) { $this->_el_data_id = $_t; }
	public function _set_el_classes($_t) { $this->_el_classes = $_t; }
	public function _set_el_label($_t) { $this->_el_label = $_t; }
	public function _set_el_opt_val($_t) { $this->_el_opt_val = $_t; }
	public function _set_el_name($_t) { $this->_el_name = $_t; }
	public function _set_el_number_inc($_t) { $this->_el_number_inc = $_t; }
	public function _set_el_number_min($_t) { $this->_el_number_min = $_t; }
	public function _set_el_number_max($_t) { $this->_el_number_max = $_t; }
	public function _set_el_value($_t) { $this->_el_value = $_t; }
	public function _set_el_style($_t) { $this->_el_style = $_t; }
	public function _set_el_type($_t) { $this->_el_type = $_t; }
	public function _set_el_width($_t) { $this->_el_width = $_t; }
	public function _set_el_yn_preselect($_t) { $this->_el_yn_preselect = $_t; }
	public function _set_el_yn_value($_t) { $this->_el_yn_value = $_t; }
	public function _set_yes($_t) { $this->_yes = $_t; }
	public function _set_no($_t) { $this->_no = $_t; }
	public function _set_el_date_name($_t) { $this->_el_date_name = $_t; }
	public function _set_el_date_value($_t) { $this->_el_date_value = $_t; }
	public function _set_top_zero_level($_t) { $this->_top_zero_level = $_t; }


}
?>
