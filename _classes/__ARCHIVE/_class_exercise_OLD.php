<?php
class _exercise{
	private $_dbh;
	private $_exercises;
	private $_topic_id;
	private $_topic_ex_id;
	private $_topic_ex_shuffle;
	private $_topic_ex_qs;
	private $_del_ex;
	private $_del_q;
	private $_ex_q_count;
	private $_ex_count;
	private $_topic_ex_num_qs;
	private $_ex_title;
	private $_ex_instructions;
	private $_ex_shuffle;
	private $_diff_max;
	private $_diff_min;
	private $_ex_answer_storage;
	private $_make_ex_tab = false;
	private $_show_difficulty = false;//Manually set during development. Set to false when live
	private $_is_logged_in;
	private $_show_labels = true;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();
		$this->_build_exercise_rows();
		$this->_list_params['l_0_ul_id'] = 'exercises';
		$this->_list_params['add_new_text'] = 'exercise';
		$this->_list_params['l_0_li_id_prefix'] = 'm';
		$this->_list_params['l_1_header_ul_id'] = 'exh';
		$this->_list_params['l_1_header_li_id_prefix'] = 'ol';
		$this->_list_params['l_1_header_title'] = 'Exercise';
		$this->_list_params['l_1_item_ul_id'] = 'exqs';
		$this->_list_params['l_1_item_li'] = "sub";
		$this->_list_params['l_1_note_title_li'] = "";
		$this->_list_params['l_1_item_num_rows'] = 1;
		$this->_list_params['img_closed_id_prefix'] = 'ic';
		$this->_list_params['img_opened_id_prefix'] = 'io';
		$this->_list_params['ajax_add_new_file'] = '_add_exercise.php';
		$this->_list_params['main_db_tbl'] = '_app_topic_ex';
		$this->_list_params['sub_db_tbls'] = array('_app_topic_ex_q');
		$this->_list_params['sub_db_tbl_fields'] = array('ex_id');
		$this->_list_params['topic_id'] = $this->_topic_id;
		$this->_list_params['rows'] = $this->_ex_rows;
		$this->_list_params['use_notes_header'] = false;
		$this->_list_params['del_main_class'] = 'del_m_ex';
		$this->_list_params['del_sub_class'] = 'del_s_ex_q';
		$this->_list_params['delete_items'] = true;
		$this->_list_params['show_labels'] = true;
		$this->_list_params['extra_jq'] = $this->_build_add_new_question_jq();


		$this->_list = new _list($this->_list_params);
	}


	private function _build_exercise_rows(){
		$this->_ex_rows = $this->_fetch_topic_exercises();
		if (!empty($this->_ex_rows) || $this->_is_logged_in){$this->_make_ex_tab = true;}

		$this->_list_params['div_class'][0] = 'question';
		$this->_list_params['div_class'][1] = 'answer';

		$_ex_count = 0;
		foreach ($this->_ex_rows as $_ex){
			$this->_topic_ex_id = $_ex['id'];
			if ($this->_is_logged_in){
				$_params['id'] = $this->_topic_ex_id;
				$_params['db_tbl'] = '_app_topic_ex';
				$_params['el_width'] = 40;
				$_params['el_width_units'] = '%';
				$_params['field_name'] = 'tex_title';
				$_params['field_value'] = $_ex['tex_title'];
				$_ex['tex_title'] = _build_varchar($_params);
				$_params['field_name'] = 'tex_instructions';
				$_params['field_value'] = $_ex['tex_instructions'];
				$_ex['tex_instructions'] = _build_textarea($_params);
				$this->_list_params['edit_fields'][$_ex_count] = $this->_build_edit_elements($_ex);
			}
			$this->_list_params['title'][$_ex_count] = $_ex['tex_title'];
			$this->_list_params['instructions'][$_ex_count] = $_ex['tex_instructions'];
			$this->_topic_ex_num_qs = $_ex['number_of_questions'];
			if ($_ex['tex_shuffle'] && $this->_is_logged_in == false){
				$this->_q_rows = $this->_pick_rnd_questions_by_difficulty();
			}else{
				$this->_q_rows = $this->_fetch_exercise_questions();
			}
			$_q_count = 0;
			foreach ($this->_q_rows as $_qs){
				$_id = $_qs['id'];
				if ($this->_is_logged_in){
					$_params['id'] = $_id;
					$_params['db_tbl'] = '_app_topic_ex_q';
					$_params['el_width'] = 100;
					$_params['el_height'] = 200;
					$_params['el_width_units'] = '%';
					$_params['el_height_units'] = 'px';
					$_params['field_name'] = 'question';
					$_params['field_value'] = $_qs['question'];
					$_qs['question'] = _build_textarea($_params);
					$_params['field_name'] = 'answer';
					$_params['field_value'] = $_qs['answer'];
					$_qs['answer'] = _build_textarea($_params);
				}else{
					$_qs['answer'] = $this->_build_answer_card($_id, $_qs['answer']);
				}

				$_q_num = $_q_count + 1;
				$this->_list_params['labels'][$_ex_count][] = $_q_num.'.';
				$this->_list_params['the_rest'][$_ex_count][$_q_count][] = $_qs['question'];
				$this->_list_params['the_rest'][$_ex_count][$_q_count][] = $_qs['answer'];
				$this->_list_params['ids'][$_ex_count][$_q_count] = $_id;
				$_q_count++;
			}
			$_ex_count++;
		}
	}


	private function _build_answer_card($_id, $_answer){
		$tmp = "<div id = 'card".$_id."' class = 'card point min-h100 w100pc'>
			<div class = 'front w100pc'>
				<img src = '_stdlib/_images/_icons/card_back.png' />
			</div>
			<div class = 'back w100pc hidden'><div class='mr20 mt10'>".$_answer."</div></div></div>";
		return $tmp;
	}

	public function _fetch_exercises(){
		return $this->_list->_build_list();
	}

	private function _build_edit_elements($_ex){
		$_id = $_ex['id'];
		$_ex_num_qs = $_ex['number_of_questions'];

		$_params = array(
				'el_type' => 'number',
				'el_number_inc' => 1,
				'el_number_min' => 1,
				'el_number_max' => 50,
				'el_name' => 'number_of_questions',
				'el_value' => $_ex_num_qs,
				'el_data_id' => $_id,
				'el_data_db_tbl' => '_app_topic_ex'
		);
		$_nqs = new _select($_params);
		$_num_qs_field = $_nqs->_build_select();

		$_ex_shuffle = $_ex['tex_shuffle'];
		if (is_logged_in()){
				$_params = array(
					'db_tbl' => '_app_topic_ex',
					'field_name' => 'tex_shuffle',
					'field_value' => $_ex_shuffle,
					'id' => $_id,
			);
			$_ex_shuffle = _build_checkbox($_params);
		}
		$_ret = "<div class = 'wrap center'>".$this->_build_add_btn($this->_topic_ex_id);
		$_ret .= "<div class = 'wrap center'><label for='tex_shuffle_".$_id."'> &nbsp;&nbsp; Shuffle: </label>".$_ex_shuffle."</div>";
		$_ret .= "<div class = 'wrap center'><label for='number_of_questions_".$_id."'> &nbsp;&nbsp; Num qs: </label>".$_num_qs_field."</div></div>";
		return $_ret;
	}

	private function _build_add_btn($_id){
		$_tmp = "<div><button type = 'button' class = 'w140 add_new_qu add' id = 'anq".$_id."'>Add new question</button></div>";
		return $_tmp;
	}

	private function _fetch_topic_exercises(){
		$_sql = 'select * from _app_topic_ex where topic_id = :topic_id order by order_num';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_rows;
	}

	private function _pick_rnd_questions_by_difficulty(){
		//This is a tricky bit. Needs a lot more thought.
		$_sql = 'select max(difficulty) as max, min(difficulty) as min from _app_topic_ex_q where ex_id = :ex_id';
		$_d = array('ex_id' => $this->_topic_ex_id);
		$_f = array('i');
		$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);

		$this->_diff_min = $_row['min'];
		$this->_diff_max = $_row['max'];
		if ($this->_diff_min === $this->_diff_max && $this->_diff_min === 1){
			$_sql = 'select * from _app_topic_ex_q where ex_id = :ex_id';
			$_d = array('ex_id' => $this->_topic_ex_id);
			$_f = array('i');
			$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
			shuffle($_rows);
			$_qus_array = array_slice($_rows, 0, $this->_topic_ex_num_qs);
		}else{
			$_num_qus = $this->_topic_ex_num_qs;
			$_levels = $this->_diff_max - $this->_diff_min + 1;
			$_num_qus_per_level = intdiv($_num_qus, $_levels) + 1;
			$_qus_array = array();
			for ($_i = 1; $_i <= $_levels; $_i++){
				$_sql = 'select * from _app_topic_ex_q where ex_id = :ex_id and difficulty = '.$_i;
				$_d = array('ex_id' => $this->_topic_ex_id);
				$_f = array('i');
				$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
				array_filter($_rows);
				shuffle($_rows);
				$_count = 0;
				while ($_count < $_num_qus_per_level){
					$_qus_array[] = $_rows[$_count];
					$_count++;
				}
			}
		}
		return $_qus_array;
	}

	private function _fetch_exercise_questions(){//returns an array of questions
		// Now load the exercise questions
		$_sql = 'select * from _app_topic_ex_q where topic_id = :topic_id and ex_id = :ex_id order by difficulty';
		$_d = array('topic_id' => $this->_topic_id, 'ex_id' => $this->_topic_ex_id);
		$_f = array('i', 'i');
		$_qus = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!$this->_is_logged_in){
			$_qus = array_slice($_qus, 0, $this->_topic_ex_num_qs);
		}
		return $_qus;
	}

	private function _build_add_new_question_jq(){
		$tmp = "$(document).on('click', '.add_new_qu', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(3);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_ex_q');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('ex_id', id);
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_lib_url__."_ajax/_add_question.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#exqs'+id).append(data);
						}
					});
				});";
		return $tmp;
	}




	public function _get_make_ex_tab(){ return $this->_make_ex_tab; }
}
?>