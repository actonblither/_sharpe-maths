<?php
class _hlf{

	public function _build_text_box($_fld, $_value, $_db_tbl, $_id, $_wdth, $_plht){
		$_params = array(
				'db_tbl' => $_db_tbl,
				'el_field_id' => $_fld,
				'el_field_value' => $_value,
				'el_width' => $_wdth,
				'el_id_value' => $_id,
				'el_place_holder' => $_plht
		);
		$_tf = new _form_element($_params);
		return $_tf->_build_text_input();
	}

	public function _build_select($_fld, $_value, $_db_tbl, $_id, $_sql, $_wdth){
		$_params = array(
				'el_type' => 'sel',
				'el_name' => $_fld,
				'el_value' => $_value,
				'el_data_id' => $_id,
				'el_data_db_tbl' => $_db_tbl,
				'db_sql' => $_sql,
				'db_display_field' => 'title',
				'el_width' => $_wdth
		);
		$_nqs = new _select($_params);
		$_nqs->_set_top_zero_level(true);
		return $_nqs->_build_select();
	}

	public function _build_chkbox($_fld, $_value, $_db_tbl, $_id){
		$_params = array(
				'db_tbl' => $_db_tbl,
				'el_field_id' => $_fld,
				'el_field_value' => $_value,
				'el_id_value' => $_id,
		);
		$_df = new _form_element($_params);
		return $_df->_build_checkbox();
	}
}