<?php
class _activity{
	private $_dbh;
	private $_act_title;
	private $_make_act_tab = false;
	private $_rows;
	private $_topic_id;
	private $_is_logged_in;
	private $_list_params;
	private $_list;
	private $_show_labels = false;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_is_logged_in = is_logged_in();
		$this->_topic_id = $_tid;
		$this->_build_l_1_rows();
		$this->_list_params['l_0_ul_id'] = 'activities';
		$this->_list_params['add_new_text'] = 'activity';
		$this->_list_params['l_0_li_id_prefix'] = 'a';
		$this->_list_params['l_1_header_ul_id'] = 'ach';
		$this->_list_params['l_1_header_li_id_prefix'] = 'ac';
		$this->_list_params['l_1_header_title'] = 'Activity';
		$this->_list_params['l_1_item_ul_id'] = 'aci';
		$this->_list_params['l_1_item_li'] = "";
		$this->_list_params['l_1_note_title_li'] = "";
		$this->_list_params['l_1_item_num_rows'] = 3;
		$this->_list_params['img_closed_id_prefix'] = 'ac';
		$this->_list_params['img_opened_id_prefix'] = 'ao';
		$this->_list_params['ajax_add_new_file'] = '_add_activity.php';
		$this->_list_params['main_db_tbl'] = '_app_topic_act';
		$this->_list_params['topic_id'] = $this->_topic_id;
		$this->_list_params['rows'] = $this->_rows;
		$this->_list_params['use_notes_header'] = true;
		$this->_list_params['del_main_class'] = 'del_m_act';
		$this->_list_params['del_sub_class'] = '';
		$this->_list_params['delete_items'] = false;
		$this->_list_params['show_labels'] = false;

		$this->_list = new _list($this->_list_params);
	}

	private function _build_l_1_rows(){
		$_sql = 'select * from _app_topic_act where topic_id = :topic_id order by order_num, id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($this->_rows) || $this->_is_logged_in){$this->_make_act_tab = true;}

		$this->_list_params['div_class'][0] = 'canv';
		$_count = 0;
		//_cl($this->_rows, 'ROWS');
		foreach ($this->_rows as $_r){
			$_id = $_r['id'];
			if ($this->_is_logged_in){
				$this->_build_form_elements($_r, $_id, $_count);
			}else{
				$this->_list_params['title'][$_count] = $_r['tact_title'];
				$this->_list_params['instructions'][$_count] = $_r['tact_instructions'];
				$this->_list_params['body'][$_count] = $_r['tact_body'];
			}
			$_count++;
		}
	}

	private function _build_form_elements($_r, $_id, $_count){
		$_params['id'] = $_id;
		$_params['db_tbl'] = '_app_topic_act';
		$_params['el_width'] = 100;
		$_params['el_height'] = 100;
		$_params['el_width_units'] = '%';
		$_params['el_height_units'] = 'px';

		$_params['field_name'] = 'tact_instructions';
		$_params['field_value'] = rvs($_r['tact_instructions']);
		$this->_list_params['instructions'][$_count] = _build_textarea($_params);

		$_params['field_name'] = 'tact_body';
		$_params['field_value'] = rvs($_r['tact_body']);
		$this->_list_params['body'][$_count] = _build_textarea($_params);


		$_params['field_name'] = 'tact_title';
		$_params['field_value'] = rvs($_r['tact_title']);
		$_params['el_width'] = 80;
		$_params['el_width_units'] = '%';
		$this->_list_params['title'][$_count] = _build_varchar($_params);
	}

	public function _fetch_activity(){
		return $this->_list->_build_list();
	}

	public function _get_make_act_tab(){return $this->_make_act_tab;}

}
?>