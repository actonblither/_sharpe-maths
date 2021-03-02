<?php
class _example{
	private $_dbh;
	private $_eg_title;
	private $_make_eg_tab = false;
	private $_rows;
	private $_topic_id;
	private $_is_logged_in;
	private $_list_params;
	private $_list;
	private $_show_labels = true;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_is_logged_in = is_logged_in();
		$this->_topic_id = $_tid;
		$this->_build_l_1_rows();
		$this->_list_params['l_0_ul_id'] = 'examples';
		$this->_list_params['add_new_text'] = 'worked_example';
		$this->_list_params['l_0_li_id_prefix'] = 'n';
		$this->_list_params['l_1_header_ul_id'] = 'egh';
		$this->_list_params['l_1_header_li_id_prefix'] = 'il';
		$this->_list_params['l_1_header_title'] = 'Worked example';
		$this->_list_params['l_1_item_ul_id'] = 'egi';
		$this->_list_params['l_1_item_li'] = "";
		$this->_list_params['l_1_note_title_li'] = "<div class='text'></div><div class='note-title blc'>Notes</div>";
		$this->_list_params['l_1_item_num_rows'] = 3;
		$this->_list_params['img_closed_id_prefix'] = 'ic';
		$this->_list_params['img_opened_id_prefix'] = 'io';
		$this->_list_params['ajax_add_new_file'] = '_add_example.php';
		$this->_list_params['main_db_tbl'] = '_app_topic_eg';
		$this->_list_params['topic_id'] = $this->_topic_id;
		$this->_list_params['rows'] = $this->_rows;
		$this->_list_params['use_notes_header'] = true;
		$this->_list_params['del_main_class'] = 'del_m_eg';
		$this->_list_params['del_sub_class'] = 'del_s_eg';
		$this->_list_params['delete_items'] = false;
		$this->_list_params['show_labels'] = true;
		$this->_list = new _list($this->_list_params);
	}

	private function _build_l_1_rows(){
		$_sql = 'select * from _app_topic_eg where topic_id = :topic_id order by order_num, id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($this->_rows) || $this->_is_logged_in){$this->_make_eg_tab = true;}

		$this->_list_params['div_class'][0] = 'text';
		$this->_list_params['div_class'][1] = 'note';
		$_count = 0;
		foreach ($this->_rows as $_r){
			$_id = $_r['id'];
			$this->_list_params['labels'][$_count][0] = "<img class='ttip' title = 'Problem' alt = 'Problem' src = './_images/_icons/problem32.png' />";
			$this->_list_params['labels'][$_count][1] = "<img class='ttip' title = 'Method' alt = 'Method' src = './_images/_icons/method32.png' />";
			$this->_list_params['labels'][$_count][2] = "<img class='ttip' title = 'Solution' alt = 'Solution' src = './_images/_icons/solution32.png' />";
			if ($this->_is_logged_in){
				$this->_build_form_elements($_r, $_id, $_count);
			}else{
				$this->_list_params['the_rest'][$_count][0][0] = $_r['teg_question'];
				$this->_list_params['the_rest'][$_count][0][1] = $_r['teg_question_note'];
				$this->_list_params['the_rest'][$_count][1][0] = $_r['teg_method'];
				$this->_list_params['the_rest'][$_count][1][1] = $_r['teg_method_note'];
				$this->_list_params['the_rest'][$_count][2][0] = $_r['teg_answer'];
				$this->_list_params['the_rest'][$_count][2][1] = $_r['teg_answer_note'];
				$this->_list_params['title'][$_count] = $_r['teg_title'];
			}
			$_count++;
		}
		//_cl($this->_list_params['the_rest']);
	}

	private function _build_form_elements($_r, $_id, $_count){
		$_params['id'] = $_id;
		$_params['db_tbl'] = '_app_topic_eg';
		$_params['el_width'] = 100;
		$_params['el_height'] = 200;
		$_params['el_width_units'] = '%';
		$_params['el_height_units'] = 'px';

		$_params['field_name'] = 'teg_question';
		$_params['field_value'] = rvs($_r['teg_question']);
		$this->_list_params['the_rest'][$_count][0][0] = _build_textarea($_params);

		$_params['field_name'] = 'teg_question_note';
		$_params['field_value'] = rvs($_r['teg_question_note']);
		$this->_list_params['the_rest'][$_count][0][1] = _build_textarea($_params);

		$_params['field_name'] = 'teg_method';
		$_params['field_value'] = rvs($_r['teg_method']);
		$this->_list_params['the_rest'][$_count][1][0] = _build_textarea($_params);

		$_params['field_name'] = 'teg_method_note';
		$_params['field_value'] = rvs($_r['teg_method_note']);
		$this->_list_params['the_rest'][$_count][1][1] = _build_textarea($_params);

		$_params['field_name'] = 'teg_answer';
		$_params['field_value'] = rvs($_r['teg_answer']);
		$this->_list_params['the_rest'][$_count][2][0] = _build_textarea($_params);

		$_params['field_name'] = 'teg_answer_note';
		$_params['field_value'] = rvs($_r['teg_answer_note']);
		$this->_list_params['the_rest'][$_count][2][1] = _build_textarea($_params);

		$_params['field_name'] = 'teg_title';
		$_params['field_value'] = rvs($_r['teg_title']);
		$_params['el_width'] = 100;
		$_params['el_width_units'] = '%';
		$this->_list_params['title'][] = _build_varchar($_params);
	}

	public function _fetch_examples(){
		return $this->_list->_build_list();
	}

	public function _get_dbh() { return $this->_dbh; }
	public function _get_examples() { return $this->_examples; }
	public function _get_make_eg_tab() { return $this->_make_eg_tab; }
	public function _get_rows() { return $this->_rows; }
	public function _get_topic_id() { return $this->_topic_id; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_examples($_t) { $this->_examples = $_t; }
	public function _set_make_eg_tab($_t) { $this->_make_eg_tab = $_t; }
	public function _set_rows($_t) { $this->_rows = $_t; }
	public function _set_topic_id($_t){$this->_topic_id = $_t;}
}
?>