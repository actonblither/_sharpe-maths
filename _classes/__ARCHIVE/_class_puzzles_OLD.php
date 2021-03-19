<?php
class _puzzle{
	private $_dbh;
	private $_pz_title;
	private $_make_pz_tab = false;
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
		$this->_list_params['l_0_ul_id'] = 'puzzles';
		$this->_list_params['add_new_text'] = 'puzzle';
		$this->_list_params['l_0_li_id_prefix'] = 'n';
		$this->_list_params['l_1_header_ul_id'] = 'pzh';
		$this->_list_params['l_1_header_li_id_prefix'] = 'pl';
		$this->_list_params['l_1_header_title'] = 'Puzzle';
		$this->_list_params['l_1_item_ul_id'] = 'pzi';
		$this->_list_params['l_1_item_li'] = "";
		$this->_list_params['l_1_note_title_li'] = "<div class='text'></div><div class='note-title blc'>Notes</div>";
		$this->_list_params['l_1_item_num_rows'] = 3;
		$this->_list_params['img_closed_id_prefix'] = 'pc';
		$this->_list_params['img_opened_id_prefix'] = 'po';
		$this->_list_params['ajax_add_new_file'] = '_add_puzzle.php';
		$this->_list_params['main_db_tbl'] = '_app_puzzles';
		$this->_list_params['topic_id'] = $this->_topic_id;
		$this->_list_params['rows'] = $this->_rows;
		$this->_list_params['use_notes_header'] = false;
		$this->_list_params['del_main_class'] = 'del_m_pz';
		$this->_list_params['del_sub_class'] = 'del_s_pz';
		$this->_list_params['delete_items'] = false;
		$this->_list_params['show_labels'] = true;
		$this->_list = new _list($this->_list_params);
	}

	public function _fetch_all_puzzles(){
		$this->_show_title = true;
		if ($this->_show_title){$this->_set_pz_page_title('General puzzle page');}
		$_sql = 'select * from _app_puzzles where display = :display and archived = :archived order by difficulty';
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $this->_build_puzzle_page($_rows);
	}

	private function _build_l_1_rows(){
		$_sql = 'select p.* from _app_puzzles p left join _app_puzzle_topic_link pt on pt.puzzle_id = p.id where p.display = :display and p.archived = :archived and pt.topic_id = :topic_id order by p.difficulty';
		$_d = array('display' => 1, 'archived' => 0, 'topic_id' => $this->_topic_id);
		$_f = array('i', 'i', 'i');
		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($this->_rows) || $this->_is_logged_in){$this->_make_pz_tab = true;}

		$this->_list_params['div_class'][0] = 'text';

		$_count = 0;
		foreach ($this->_rows as $_r){
			$_id = $_r['id'];


			$this->_list_params['labels'][$_count][0] = "<img class='ttip' title = 'Puzzle' alt = 'Puzzle' src = './_images/_icons/puzzle32.png' />";
			$this->_list_params['labels'][$_count][1] = $this->_fetch_icon('hint', 'hc', $_id);
			$this->_list_params['labels'][$_count][2] = $this->_fetch_icon('solution', 'sc', $_id);
			$this->_list_params['labels'][$_count][3] = $this->_fetch_icon('explanation', 'ec', $_id);

			if ($this->_is_logged_in){
				$this->_build_form_elements($_r, $_id, $_count);
			}else{
				$this->_list_params['the_rest'][$_count][0] = $_r['pz_puzzle'];
				$this->_list_params['the_rest'][$_count][1] = "<div id = 'hc".$_id."' class='center p5 wrap hidden'>".$_r['pz_hint']."</div>";
				$this->_list_params['the_rest'][$_count][2] = "<div id = 'sc".$_id."' class='center p5 wrap hidden'>".$_r['pz_solution']."</div>";
				$this->_list_params['the_rest'][$_count][3] = "<div id = 'ec".$_id."' class='center p5 wrap hidden'>".$_r['pz_explanation']."</div>";
				$this->_list_params['title'][$_count] = $_r['pz_title'];
			}
			$_count++;
		}

	}

	private function _fetch_icon($_pr, $_pre, $_id){
		$_ico = $_pr."32.png";
		if ($_pr == 'puzzle'){$_t = 'Puzzle problem'; $_reveal = ''; $_point = '';}
		if ($_pr == 'hint'){$_t = 'Click here for a hint.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'solution'){$_t = 'Click here for the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'explanation'){$_t = 'Click here for an explanation of the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		$_src = __s_app_icon_url__.$_ico;
		$tmp = "<img src = '".$_src."' class = '".$_reveal."w32 ttip".$_point."' title = '".$_t."' data-id = '".$_id."' data-text-div = '".$_pre."' />";
		return $tmp;
	}

	private function _build_form_elements($_r, $_id, $_count){
		$_params['id'] = $_id;
		$_params['db_tbl'] = '_app_puzzle';
		$_params['el_width'] = 100;
		$_params['el_height'] = 200;
		$_params['el_width_units'] = '%';
		$_params['el_height_units'] = 'px';

		$_params['field_name'] = 'pz_puzzle';
		$_params['field_value'] = rvs($_r['pz_puzzle']);
		$this->_list_params['the_rest'][$_count][0] = _build_textarea($_params);

		$_params['field_name'] = 'pz_hint';
		$_params['field_value'] = rvs($_r['pz_hint']);
		$this->_list_params['the_rest'][$_count][1] = _build_textarea($_params);

		$_params['field_name'] = 'pz_solution';
		$_params['field_value'] = rvs($_r['pz_solution']);
		$this->_list_params['the_rest'][$_count][2] = _build_textarea($_params);

		$_params['field_name'] = 'pz_explanation';
		$_params['field_value'] = rvs($_r['pz_explanation']);
		$this->_list_params['the_rest'][$_count][3] = _build_textarea($_params);

		$_params['field_name'] = 'pz_title';
		$_params['field_value'] = rvs($_r['pz_title']);
		$_params['el_width'] = 100;
		$_params['el_width_units'] = '%';
		$this->_list_params['title'][$_count] = _build_varchar($_params);
	}

	public function _fetch_topic_puzzles(){
		return $this->_list->_build_list();
	}

	public function _get_dbh() { return $this->_dbh; }
	public function _get_puzzles() { return $this->_puzzles; }
	public function _get_make_pz_tab() { return $this->_make_pz_tab; }
	public function _get_rows() { return $this->_rows; }
	public function _get_topic_id() { return $this->_topic_id; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_examples($_t) { $this->_examples = $_t; }
	public function _set_make_eg_tab($_t) { $this->_make_eg_tab = $_t; }
	public function _set_rows($_t) { $this->_rows = $_t; }
	public function _set_topic_id($_t){$this->_topic_id = $_t;}
}
?>