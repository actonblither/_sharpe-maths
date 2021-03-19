<?php
class _multiple_select{
	private $_db_tbl_link;
	private $_db_tbl_field1;
	private $_db_tbl_field2;
	private $_id;
	private $_select_list_db_tbl;
	private $_select_list_id_prefix;
	private $_dbh;

	public function __construct($_params){
		$this->_dbh = new _db();
		$this->_db_tbl_link = rvs($_params['db_tbl_link']);
		$this->_db_tbl_field1 = rvs($_params['db_tbl_field1']);
		$this->_db_tbl_field2 = rvs($_params['db_tbl_field2']);
		$this->_select_list_db_tbl = rvs($_params['select_list_db_tbl']);
		$this->_select_list_id_prefix = rvs($_params['select_list_id_prefix']);
		$this->_id = rvz($_params['this_item_id']);
	}



	public function _build_link_self_ref_select(){
		$_sql = 'select * from '.$this->_select_list_db_tbl.' where display = :display and archived = :archived order by left(title, 1);';
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);

		$tmp = "<select id = '".$this->_select_list_id_prefix.$this->_id."' multiple data-id = '".$this->_id."' data-field1 = '".$this->_db_tbl_field1."' data-field2 = '".$this->_db_tbl_field2."' data-db-tbl = '".$this->_db_tbl_link."' data-el-type = 'sel_mult' data-link-self-ref = '1' class = 'sel-link-field h300'>";

		if (!empty($_rows)){
			for ($i = 0; $i < count($_rows); $i++){
				$_sel_text = '';
				//Only output the option if it does not self-connect
				if ($_rows[$i]['id'] !== $this->_id){
					$_sql = "(select ".$this->_db_tbl_field1.", ".$this->_db_tbl_field2." from ".$this->_db_tbl_link." where (".$this->_db_tbl_field1." = ".$_rows[$i]['id'].") and (".$this->_db_tbl_field2." = ".$this->_id.")) union (select ".$this->_db_tbl_field1.", ".$this->_db_tbl_field2." from ".$this->_db_tbl_link." where (".$this->_db_tbl_field1." = ".$this->_id.") and (".$this->_db_tbl_field2." = ".$_rows[$i]['id']."))";
					$_sel_row = $this->_dbh->_fetch_db_row($_sql);
					if ($_sel_row){
						if ($_sel_row[$this->_db_tbl_field1] == $_rows[$i]['id'] && $_sel_row[$this->_db_tbl_field2] == $this->_id || $_sel_row[$this->_db_tbl_field2] == $_rows[$i]['id'] && $_sel_row[$this->_db_tbl_field1] == $this->_id){
							$_sel_text = "selected = 'selected'";
						}
					}//END OF IF THEN
					$tmp .= "<option value='".$_rows[$i]['id']."' ".$_sel_text.">".$_rows[$i]['title']."</option>";
				}//END OF if ($_sel_row)

			}
		}
		$tmp .= "</select>";
		return $tmp;
	}

	public function _build_link_select(){
		$_sql = 'select * from '.$this->_select_list_db_tbl.' where display = :display and archived = :archived order by left(title, 1);';
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);//this is an array of topics

		$tmp = "<select id = '".$this->_select_list_id_prefix.$this->_id."' multiple data-id = '".$this->_id."' data-field1 = '".$this->_db_tbl_field1."' data-field2 = '".$this->_db_tbl_field2."' data-db-tbl = '".$this->_db_tbl_link."' data-el-type = 'sel_mult' data-link-self-ref = '0' class = 'sel-link-field h300'>";
		if (!empty($_rows)){
			for ($i = 0; $i < count($_rows); $i++){
				$_sel_text = '';
				$_sql = "select * from ".$this->_db_tbl_link." where ".$this->_db_tbl_field1." = ".$_rows[$i]['id'];

				$_sel_row = $this->_dbh->_fetch_db_row($_sql);
				if ($_sel_row){
					if ($_sel_row[$this->_db_tbl_field1] == $_rows[$i]['id']){
						$_sel_text = "selected = 'selected'";
					}

				}//END OF IF THEN
				$tmp .= "<option value='".$_rows[$i]['id']."' ".$_sel_text.">".$_rows[$i]['title']."</option>";
			}
		}
		$tmp .= "</select>";
		return $tmp;
	}
}


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