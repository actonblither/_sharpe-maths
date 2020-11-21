<?php
class _list extends _setup{


	private $_gen_top_img;
	private $_gen_table;
	private $_list_params;

	private $_list_show_tabs = false;
	private $_list_tab_tpl = '';
	private $_list_form_container;
	private $_list_fields;
	private $_list_fields_o;// Original list parameters

	private $_list_sort;
	private $_list_enable_sort;
	private $_list_tab_row;
	private $_list_title_row;
	private $_list_filter_row;
	private $_list_header_row;
	private $_list_rows;
	private $_list_function_parameter_type;

	private $_list_title_text;
	private $_list_id_prefix;
	private $_list_item_view_target;

	private $_list_vertical_align;
	private $_list_li_id_prefix;
	private $_list_show_filter_row;
	private $_list_show_top_tab;

	public function __construct(){
		parent::__construct();
		//The following is an example to get things going
		$this->_set_cfg_title('frac');
		$this->_fetch_config_class_path();
		$this->_id = parent::_get_id();
	}

	public function _display_list(){
		$this->_create_list();
		echo '<script>$(document).ready(function(){';
		echo $this->_build_jq_filter_keyup_code();
		echo $this->_build_jq_sortable_code();
		echo $this->_build_jq_view_click();
		echo '});</script>';
		echo $this->_start_list_form_container();
		echo $this->_get_list_title_row();
		echo $this->_get_list_filter_row();
		echo $this->_get_list_header_row();
		echo $this->_get_list_rows();
		echo $this->_end_list_form_container();
	}



	private function _create_list(){
		// BUILD ALL SECTIONS OF THE LIST
		//	1. FIND THE _CONFIG FILE
		// 	2. INTEROGATE THE _CONFIG FILE
		//	3. BUILD THE TITLE BAR
		//	4. BUILD THE FILTER BAR
		//	5. BUILD THE COLUMN HEADER BAR
		//	6. FETCH THE DATA ROWS ARRAY
		//	7. BUILD THE LIST ROWS
		$this->_fetch_config_class_path();
		$this->_load_list_cfg_parameter_values();

		$this->_set_list_title_row($this->_make_list_title_bar());
		$this->_set_list_filter_row($this->_make_list_filter_row_li());
		$this->_set_list_header_row($this->_make_list_column_header_li());
		$this->_fetch_data_rows();
		$this->_set_list_rows($this->_make_list_rows());

	}

	private function _fetch_config_class_path(){
		$class_title = $this->_get_cfg_title();
		if (!empty($class_title)){
			$sql = 'select * from __sys_main_cfg where title = :title';
			$_d = array('title' => $class_title);
			$_f = array('s');
			$row = $this->_dbh->_fetch_db_row_p($sql, $_d, $_f);
		}

		if (!empty($row['path'])){
			//Look in both the _stdlib _config folders and also the _app _config folders
			$_lib_config_folder = __s_lib_folder__.$row['path'];
			$_app_config_folder = __s_app_folder__.$row['path'];

			if (file_exists($_app_config_folder)){
				$this->_set_cfg_path($_app_config_folder);
			}else{
				$this->_set_cfg_path($_lib_config_folder);
			}
			$this->_set_cfg_class($row['class']);
		}else{
			$this->_set_cfg_path(false);
			$this->_set_cfg_class(false);
		}
	}

	private function _load_list_cfg_parameter_values(){
		$this->_fetch_config_class_path();
		if (!empty($this->_get_cfg_class())){
			$this->_load_page_cfg_file();
			$this->_load_list_params();
			$this->_load_general_params();
			$this->_split_list_fields();
		}
		return null;
	}

	private function _load_page_cfg_file(){
		$class = $this->_get_cfg_class();
		if (file_exists($this->_get_cfg_path())) {
			include_once ($this->_get_cfg_path());
		}
		// Get section parameters from the _config class
		$fc = new $class();
		if (isset($this->_get_id)){$fc->_set_section_id($this->_id);}

		$this->_params = $fc->_get_params();
		if (empty($this->_id)) {
			$this->_id = rvz($this->_params['id']);
		}
		if (empty($this->_id)) {
			$this->_id = rvz($_REQUEST['id']);
		}
		return null;
	}

	private function _load_list_params(){
		if (__s_single_tab_approach__ == 1){$this->_app_target = '_self';}else{$this->_app_target = '_blank';}
		$this->_list_id_prefix = rvs($this->_params['list_id_prefix'], '');
		$this->_list_order_by = rvs($this->_params['list_order_by']);
		$this->_list_show_counter = rvb($this->_params['list_show_counter'], false);
		$this->_list_vertical_align = rvs($this->_params['list_vertical_align'], 'vt');
		$this->_list_li_id_prefix = rvs($this->_params['list_li_id_prefix'], 'dd');
		$this->_list_enable_sort = rvb($this->_params['list_enable_sort'], true);
		$this->_list_form_container = rvs($this->_params['list_form_container'], 'list_form_container');
		$this->_list_show_filter_row = rvb($this->_params['list_show_filter_row'], true);
		$this->_list_show_title = rvb($this->_params['list_show_title'], true);
		$this->_list_show_top_tab = rvb($this->_params['list_show_top_tab'], true);
		$this->_list_item_view_target = rvs($this->_params['list_item_view_target']);
		$this->_list_sql_code = rvs($this->_params['list_sql_code']);
		$this->_list_sql_code_d = rva($this->_params['list_sql_code_d']);
		$this->_list_sql_code_f = rva($this->_params['list_sql_code_f']);
		$this->_list_title_filter_label = rvs($this->_params['list_title_filter_label']);
		$this->_list_title_text = rv($this->_params['list_title_text']);
		$this->_list_top_tab = rv($this->_params['list_top_tab']);

		$_list_fields = rva($this->_params['list_fields']);

		$count = 0;
		foreach ($_list_fields as $l){
			$_list_array = explode('::', $l);
			$this->_list_fields[$count] = $_list_array[0].'::'.$_list_array[3];
			$this->_list_width[$count] = $_list_array[1];
			$this->_list_align[$count] = $_list_array[2];
			$this->_list_labels[$count] = $_list_array[4];
			$this->_list_filter[$count] = rv($_list_array[5]);
			rv($_list_array[6]);
			if (instr('||', $_list_array[6])){
				$arr = explode('||', $_list_array[6]);
				$this->_list_function_parameter_type[$_list_array[3]] = $arr[1];
				$this->_list_functions[$_list_array[3]] = $arr[0];
			}else{
				$this->_list_functions[$_list_array[3]] = rv($_list_array[6]);
			}
			$this->_list_sort[$count] = rv($_list_array[7]);
			if ($this->_list_sort[$count] == 'no-sort'){
				$this->_list_show_sort_arrow[$count] = false;
			}else{
				$this->_list_show_sort_arrow[$count] = true;
			}
			$count++;
		}
		$this->_list_fields_o = $this->_list_fields;
		return null;
	}

	private function _load_general_params(){
		$this->_gen_title_identifier_sql = rv($this->_params['gen_title_identifier_sql']);
		$this->_gen_top_img = rv($this->_params['gen_page_top_img'], 'default32.png');
		$this->_gen_table = rvs($this->_params['gen_table']);
		return null;
	}

	private function _split_list_fields(){
		if (!empty($this->_list_fields_o)){
			for($i=0; $i < count($this->_list_fields_o); $i++){
				list($type, $field) = explode('::', $this->_list_fields_o[$i]);

				if (isset($_POST[$field])){
					$_value = $_POST[$field];
				}else{
					$_value = null;
				}
				if (is_array($_value)){$type = 'a';}

				$frm[] = _var_filter($_value, $type);
				$fmt[] = rvs($type);
				$fields[] = rvs($field);
			}
			$this->_list_fields = $fields;
			$this->_list_fields_format = $fmt;
			$this->_list_fields_values = $frm;
		}
	}

	private function _start_list_form_container(){
		$_lf_container = $this->_get_list_id_prefix().'_'.$this->_get_list_form_container();
		return "<div id = '".$_lf_container."'>";
	}

	private function _end_list_form_container(){
		return "</div>";
	}

	private function _make_list_title_bar(){
		$tmp = "
		<div class = 'page-title list-title'>
		<img src = '".__s_lib_url__."/_images/_icons/32/".$this->_gen_top_img."' alt = '".ucwords($this->_main)."' />";

		if (instr('|', $this->_list_title_text)){
			$this->_tidy_list_page_title('list');
		}
		// use html_entity_decode so that ampersands et al appear correctly.
		$title = html_entity_decode($this->_list_title_text);
		$tmp .= $title;
		$tmp .= "<div id = '".$this->_list_id_prefix."_list-title-counter' class = 'title-counter'></div>
	</div>";
		return $tmp;
	}

	private function _make_list_filter_row_li(){
		$_filter_id = $this->_list_id_prefix.'_list-filter';
		$li_class = 'filter-row';
		$tmp = "<ul>";
		$tmp .= "
		<li class = '".$li_class."'>
		<div class = 'b pl5' style = 'min-width:250px;'>
			<label for = '".$_filter_id."'>".rv($this->_list_title_filter_label)."</label>
			<input id = '".$_filter_id."' class = 'w150 mr5 ml5 ttip' style = 'height:26px;' title = 'Type into this field to filter the list down to those records fields, contains those letters.' type = 'text' />
		</div>";

		if (rvb($this->_list_parent_sel['show'], false)){
			$tmp .= "<div class = 'b ml10' style = 'min-width:350px;'>".rv($this->_list_parent_sel['label']).":";
			$tmp .= $this->_build_parent_select_top( $this->_list_parent_sel['unset'], $this->_list_parent_sel['select_display_field']);
			$tmp .= "</div>";
		}

		$live_count = 0;
		if (!empty($this->_list_rows)){
			foreach ($this->_list_rows as $r){
				if (isset($r['archived']) && $r['archived'] == 0){$live_count++;}
			}
		}
		$tmp .= "</li></ul>";
		return $tmp;
	}

	private function _make_list_column_header_li(){
		$_pre_sortable_cols = 0;
		$tmp= "<ul><li id = '".$this->_list_id_prefix."_list-column-header' class = 'column-header'>";
		if ($this->_list_show_counter){
			$tmp .= "<div class = 'list-row-element left w2pc c'></div>";
			$_pre_sortable_cols++;
		}

		$tmp .= "<div class = 'list-row-element c w5pc'>View</div>";

		for($i = 0; $i < count($this->_list_labels); $i++){
			$align = rvs($this->_list_align[$i]);
			$align = convert_align_to_flex_class($align);
			$datatype = _get_data_field_type(rv($this->_list_fields_o[$i]));
			if ($datatype == 'd' || $datatype == 'dt' || $datatype == 'dob'){
				$date = 'true';
			}else{
				$date = 'false';
			}
			$tmp .= "<div class = 'list-row-element ".$align."' style = 'width:".$this->_list_width[$i]."%;'>";
			if($this->_list_show_sort_arrow[$i]){
				$_sort_fn = "sort_list(`".$this->_list_id_prefix."`,".$i.",".$date.",".$_pre_sortable_cols.");";
				$tmp .= "<img onclick = '".$_sort_fn."' class = 'sort-icon point mr3 ttip' alt = 'Sort' title = 'Sort by ".stripslashes($this->_list_labels[$i])."' src = '".__s_lib_url__."/_images/_icons/sort12.png' />";
			}
			$tmp.=stripslashes(ucfirst($this->_list_labels[$i]));
			$tmp .= "</div>";
		}
		$tmp .= "<div class = 'list-row-element right'></div></li></ul>";
		return $tmp;
	}

	private function _fetch_data_rows(){
		$_sql = $this->_list_sql_code;
		$_d = $this->_list_sql_code_d;
		$_f = $this->_list_sql_code_f;
		$this->_list_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
	}

	private function _make_list_rows(){
		$tmp = "<ul id = '".$this->_list_id_prefix."_list-sortable-list' class = 'sortable-list'>";
		$tbl = false;
		$_rows = $this->_get_list_rows();
		if (!empty($_rows)){
			$this->_counter = 1;
			foreach($_rows as $r){
				$tmp .= $this->_make_list_row($r);
				$this->_counter++;
			}
		}
		$tmp .= "</ul>";
		return $tmp;
	}// end function

	private function _make_list_row($r){
		$tmp = "<li class = 'main-list-row sort ait pt5 pb3' id = '".$this->_list_li_id_prefix.$r['id']."'>";
		$tmp .= "<div class = 'list-row-element left w5pc c ".$this->_list_vertical_align."'>";
		$tmp .= "<img class = 'point view' id = 'v_".$r['id']."' src = '".__s_lib_url__."/_images/_icons/20/view20.png' alt = 'View record' title = 'View record' />";
		$tmp .= "</div>";

		for($i = 0; $i < count($this->_list_labels); $i++){
			$align = convert_align_to_flex_class($this->_list_align[$i]);
			list($datatype, $field) = explode('::', $this->_list_fields_o[$i]);
			rv($field);
			rv($datatype);
			rv($r[$field]);

			$tmp .= "<div id = 'edit-".$field."-".$r['id']."' ";
			if ($this->_list_filter[$i] == 'filter'){
				$tmp .= " class = 'list-row-element ".$this->_list_vertical_align." list-filter-field ";
				$tmp .= $align."'";
			}else{
				$tmp .= "class = '".$align." ".$this->_list_vertical_align."'";
			}
			$tmp .= " style = 'width:".$this->_list_width[$i]."%;'>";
			// 2. Test for function present
			if (!empty($this->_list_functions[$field])){
				if (!isset($this->_list_fields[$i])){
					$value = trim(stripslashes(htmlspecialchars_decode(nl2br(($r[$field])),ENT_QUOTES)));
				}else{
					$value = $r;
				}
				$fn_name = rv($this->_list_functions[$field]);
				rv($this->_list_function_parameter_type[$field]);
				if ($this->_list_function_parameter_type[$field] == 'a'){
					if (!empty($fn_name)){$t = $this->{$fn_name}($r);}
				}else{
					if (!empty($fn_name)){$t = $this->{$fn_name}($r[$field]);}
				}

				$tmp .= trim(stripslashes(htmlspecialchars_decode(nl2br(rv($t)),ENT_QUOTES)));
			}else{
				$value = trim(stripslashes(htmlspecialchars_decode(nl2br(rv($r[$field])),ENT_QUOTES)));
				// Test for a currency type ('m') and format accordingly.
				if ($datatype == 'm'){
					$locale = 'en_GB';
					$currency = 'GBP';
					$fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
					$value = $fmt->formatCurrency((float) $value, $currency);
				}

			}
			if (!empty($this->_list_inline_edit_fields) && array_key_exists($field, $this->_list_inline_edit_fields)){
				$tmp .= "<img src = '".__s_lib_url__."/_images/_icons/16/inline-edit16.png' class = 'inline-editor point mr5' id = 'iv-".$field.'-'.$r['id']."' data-id = '".$r['id']."' data-field = '".$field."' />";
				$_v = $this->_list_inline_edit_fields[$field];
				$_bits = explode('::', $_v);
				$_params['type'] = rv($_bits[0]);
				$_params['width'] = rv($_bits[1]);
				$_params['height'] = rv($_bits[2]);
				$_params['field'] = rv($field);
				$_params['value'] = rv($value);
				$_params['id'] = rv($r['id']);

				if ($this->_list_inline_edit && is_string($value) && !empty($_params['type'])){
					$_el = new _inline_element($_params);
					$tmp .= $_el->_make_element();
				}
			}
			if (!is_array($value)){
				$tmp .= "<div id = 'it-".$field."-".$r['id']."' data-id = '".$r['id']."' data-field = '".$field."'>".$value."</div>";
			}
			$tmp .= "</div>";

		}
		$tmp .= "<div class = 'list-row-element right'></div>";
		$tmp .= "</li>";

		return $tmp;
	}

	private function _format_date($date, $format = 'd-m-Y'){
		if ($this->_blank_date($date)){
			return date($format, strtotime($date));
		}else{
			return '';
		}
	}
	private function _format_dmY_date($date){
		if (!empty($date) && $date != '0000-00-00' && $date != '0000-00-00 00:00' && $date != '0000-00-00 00:00:00'){
			return date('d-m-Y',strtotime($date));
		}else{
			return false;
		}
	}
	private function _format_dmYHi_date($date){
		if (!empty($date) && $date != '0000-00-00' && $date != '0000-00-00 00:00' && $date != '0000-00-00 00:00:00'){
			return date('d-m-Y H:i',strtotime($date));
		}else{
			return false;
		}
	}
	private function _format_Hi_date($date){
		if (!empty($date)){
			return date('H:i',strtotime($date));
		}else{
			return false;
		}
	}
	private function _format_His_date($date){
		if (!empty($date)){
			return date('H:i:s',strtotime($date));
		}else{
			return false;
		}
	}
	private function _format_dmYHis_date($date){
		if (!empty($date) && $date != '0000-00-00' && $date != '0000-00-00 00:00:00' && $date != '0000-00-00 00:00'){
			return date('d-m-Y H:i:s',strtotime($date));
		}else{
			return false;
		}
	}


	/// JQ FUNCTIONS

	private function _build_jq_filter_keyup_code(){
		$tmp = "
			$(document).on('keyup','#".$this->_list_id_prefix."_list-filter', function(){
				var str = $(this).val().toLowerCase();
				$('.list-filter-field').parent('li').filter(function(){
					$(this).toggle($(this).text().toLowerCase().indexOf(str) > -1)
				});
			});
		";
		return $tmp;
	}

	private function _build_jq_sortable_code(){
		$tmp = "
		$('#".$this->_list_id_prefix."_list-sortable-list').sortable({
		items: 'li',
		update: function(event, ui) {
		var new_list = $(this).sortable('toArray').toString();
		var fd = new FormData();
		fd.set('nlist', new_list);
		fd.set('gen_table', '$this->_gen_table');
		fd.set('app_folder', '".base64_encode(__s_app_folder__)."');
				$.ajax({
					type: 'POST',
					async : true,
					cache : false,
					processData	: false,
					contentType	: false,
					url: '".__s_lib_url__."_ajax/_record_order_update.php',
					data: fd,
					dataType: 'json',
					success: function (data) {}
				});
			}
		});".PHP_EOL;

		if ($this->_list_enable_sort === false){
			$tmp.= "$('#".$this->_list_id_prefix."_list-sortable-list').sortable('disable');";
		}

		return $tmp;
	}

	private function _build_jq_view_click(){

			$tmp = "
			$(document).on('click','.view', function(e){
				var el_id = $(this).attr('id').substring(2);
console.log(el_id);
				e.stopImmediatePropagation();
				window.location.href = 'index.php?main=topic&id='+el_id;
			});";
return $tmp;
	}




	public function _get_list_params() { return $this->_list_params; }
	public function _get_list_show_tabs() { return $this->_list_show_tabs; }
	public function _get_list_tab_tpl() { return $this->_list_tab_tpl; }
	public function _get_list_sql() { return $this->_list_sql; }
	public function _get_list_sql_d() { return $this->_list_sql_d; }
	public function _get_list_sql_f() { return $this->_list_sql_f; }
	public function _get_list_tab_row() { return $this->_list_tab_row; }
	public function _get_list_title_row() { return $this->_list_title_row; }
	public function _get_list_filter_row() { return $this->_list_filter_row; }
	public function _get_list_header_row() { return $this->_list_header_row; }
	public function _get_list_rows() { return $this->_list_rows; }
	public function _get_list_form_container(){ return $this->_list_form_container; }
	public function _get_list_id_prefix(){ return $this->_list_id_prefix; }
	public function _get_list_li_id_prefix(){ return $this->_list_li_id_prefix; }

	public function _set_list_params($_t) { $this->_list_params = $_t; }
	public function _set_list_show_tabs($_t) { $this->_list_show_tabs = $_t; }
	public function _set_list_tab_tpl($_t) { $this->_list_tab_tpl = $_t; }
	public function _set_list_sql($_t) { $this->_list_sql = $_t; }
	public function _set_list_sql_d($_t) { $this->_list_sql_d = $_t; }
	public function _set_list_sql_f($_t) { $this->_list_sql_f = $_t; }
	public function _set_list_tab_row($_t) { $this->_list_tab_row = $_t; }
	public function _set_list_title_row($_t) { $this->_list_title_row = $_t; }
	public function _set_list_filter_row($_t) { $this->_list_filter_row = $_t; }
	public function _set_list_header_row($_t) { $this->_list_header_row = $_t; }
	public function _set_list_rows($_t) { $this->_list_rows = $_t; }
	public function _set_list_form_container($_t) { $this->_list_form_container = $_t; }
	public function _set_list_id_prefix($_t) { $this->_list_id_prefix = $_t; }
	public function _set_list_li_id_prefix($_t) { $this->_list_li_id_prefix = $_t; }
}
?>