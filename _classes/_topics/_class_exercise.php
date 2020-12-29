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
	private $_show_difficulty = true;//Manually set during development. Set to false when live

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
	}

	private function _pick_questions_by_difficulty(){
		//This is a tricky bit. Needs a lot more thought.
		$_sql = 'select max(difficulty) as max, min(difficulty) as min from _app_topic_ex_q where ex_id = :ex_id';
		$_d = array('ex_id' => $this->_topic_ex_id);
		$_f = array('i');
		$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);

		$this->_diff_min = $_row['min'];
		$this->_diff_max = $_row['max'];
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
		return $_qus_array;
	}

	public function _fetch_exercises(){
		$tmp = $this->_build_exercises();
		$tmp .= $this->_ex_answer_storage;
		return $tmp;
	}

	private function _build_exercises(){
		$tmp = "<script>";
		$tmp .= "$(document).ready(function(){".PHP_EOL;
		if (is_logged_in()){
			$tmp .= $this->_build_del_ex_jq_code();
			$tmp .= $this->_build_del_q_jq_code();
			$tmp .= $this->_build_add_new_question_jq();
			$tmp .= $this->_build_add_new_exercise_jq();
		}
		$tmp .= "});</script>";

		$_rows = $this->_fetch_topic_exercises();
		if (!empty($_rows) || is_logged_in()){$this->_make_ex_tab = true;}

		if (is_logged_in()){
			$tmp .= $this->_build_add_new_exercise_btn();
			$tmp .= "<ul id = 'exercises' class = 'sortable-list'>";
		}else{
			$tmp .= "<ul id = 'exercises' class = 'flist'>";
		}
		$this->_ex_count = 1;
		if (!empty($_rows)){
			foreach ($_rows as $_row){
				$tmp .= "<li id = 'e".$_row['id']."' data-db_tbl='_app_topic_ex' class='ex'>";
				$tmp .= $this->_build_exercise($_row);
				$tmp .= "</li>";
				$this->_ex_count++;
			}
		}
		$tmp .= "</ul>";
		$this->_topic_ex = $tmp;
		return $this->_topic_ex;
	}

	private function _build_exercise($_row){
		$this->_topic_ex_id = $_row['id'];
		$this->_topic_ex_num_qs = $_row['number_of_questions'];
		$this->_ex_title = $_row['title'];
		$this->_ex_instructions = $_row['instructions'];
		$this->_ex_shuffle = $_row['shuffle'];
		if (is_logged_in()){
			$this->_ex_title = $this->_build_varchar('_app_topic_ex', 'title', $this->_ex_title, $this->_topic_ex_id);
			$this->_ex_instructions = $this->_build_text('_app_topic_ex', 'instructions', $this->_ex_instructions, $this->_topic_ex_id);
			$this->_ex_shuffle = $this->_build_checkbox('_app_topic_ex', 'shuffle', $this->_ex_shuffle, $this->_topic_ex_id);
			return $this->_build_ex_edit_list();
		}else{
			return $this->_build_ex_view_list();
		}
	}


	private function _build_ex_view_list(){
		$tmp = "<ul id = 'exh".$this->_topic_ex_id."' class = 'topic_exercise'>";
		$tmp .= "<li id = 'aa".$this->_topic_ex_id."' class='w100pc open_ex point'><div class='ex_eg w100pc'>";
		$tmp .= "<div class='w32 hauto'><img title = 'Click to open exercise.' alt = 'Open' class = 'open_ex point ttip' id = 'ao".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/closed.png' />";
		$tmp .= "<img title = 'Click to close exercise.' alt = 'Close' class = 'hidden open_ex point ttip' id = 'ac".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
		$tmp .= "<h3>Exercise $this->_ex_count: $this->_ex_title</h3></div></li>";
		$tmp .= "</ul>";
		$tmp .= $this->_build_ex_view_questions_list();
		return $tmp;
	}

	private function _build_ex_view_questions_list(){
		$this->_ex_answer_storage = '';
		// Now load the exercise questions
		if ($this->_ex_shuffle){
			$this->_topic_ex_qs = $this->_pick_questions_by_difficulty();
		}else{
			$this->_topic_ex_qs = $this->_fetch_exercise_questions();
		}

		$tmp = "<ul id = 'exqs".$this->_topic_ex_id."' class = 'topic_exercise hidden'>";
		if (!empty($this->_ex_instructions)){
			$tmp .= "<li class = 'instructions'><div class='cwrap'><strong>Instructions:</strong></div><div class='cwrap'>$this->_ex_instructions</div></li>";
		}
		if (!empty($this->_topic_ex_qs)){
			$_exq_count = 0;
			do {
				$_qid = $this->_topic_ex_qs[$_exq_count]['id'];
				$_q_count = $_exq_count + 1;
				$_question = $this->_topic_ex_qs[$_exq_count]['question'];
				$_answer = $this->_topic_ex_qs[$_exq_count]['answer'];
				$tmp .= "<li id = 'e".$_qid."' class = 'wrap'>";
				$tmp .= "<div class = 'label vt r b w30'>Q".$_q_count."</div>";
				if ($this->_show_difficulty){
					$tmp .= "<div>(".$this->_topic_ex_qs[$_exq_count]['difficulty'].")</div>";
				}
				$tmp .= "<div class = 'question'>".$_question."</div>";
				$tmp .= "<div class = 'answer r'>
			<img id = 'eye".$_qid."' class = 'w32 h32 point eye' src = '_stdlib/_images/_icons/answer.png' />
			<div id = 'ans".$_qid."' class = 'hidden eye point mr5 wrap'>".$_answer."</div>
		</div></li>";

				$_exq_count++;
			} while ($_exq_count < $this->_topic_ex_num_qs && !empty($this->_topic_ex_qs[$_q_count]['id']));
		}
		$tmp .= "</ul>";
		return $tmp;
	}

	private function _build_checkbox($_db_tbl, $_field_name, $_field_value, $_data_id){
		$_el = new _form_element();
		$_el->_set_el_field_id($_field_name);
		$_el->_set_el_field_value($_field_value);
		$_el->_set_db_tbl($_db_tbl);
		$_el->_set_el_id_value($_data_id);
		return $_el->_build_checkbox();
	}

	private function _build_text($_db_tbl, $_field_name, $_field_value, $_data_id){
		$_el = new _form_element();
		$_el->_set_el_field_id($_field_name);
		$_el->_set_el_field_value($_field_value);
		$_el->_set_db_tbl($_db_tbl);
		$_el->_set_el_id_value($_data_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(200);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('px');
		return $_el->_build_textarea();
	}

	private function _build_varchar($_db_tbl, $_field_name, $_field_value, $_data_id){
		$_el = new _form_element();
		$_el->_set_el_field_id($_field_name);
		$_el->_set_el_field_value($_field_value);
		$_el->_set_db_tbl($_db_tbl);
		$_el->_set_el_id_value($_data_id);
		$_el->_set_el_width(500);
		$_el->_set_el_width_units('px');
		return $_el->_build_text_input();
	}

	private function _build_add_new_exercise_btn(){
		$tmp = "<button type = 'button' class = 'w150 add_new_ex add mt5 ml10' id = 'to".$this->_topic_id."'>Add new exercise</button>";
		return $tmp;
	}



	private function _build_del_ex_jq_code($auto = true){
		$this->_del_ex = new _delete();
		$this->_del_ex->_set_db_main_tbl('_app_topic_ex');
		$this->_del_ex->_set_db_sub_tbls(array('_app_topic_ex_q'));
		$this->_del_ex->_set_db_sub_tbl_fields(array('ex_id'));
		$this->_del_ex->_set_img_class('delete_exercise');
		$this->_del_ex->_set_list_id('ul#exh');
		$this->_del_ex->_set_sub_list_id('ul#exqs');
		$this->_del_ex->_set_add_script_tags(false);
		$this->_del_ex->_set_add_document_ready(false);
		if ($auto){return $this->_del_ex->_delete_jq();}else{return $this->_del_ex;}
	}
	public function _build_del_q_jq_code($auto = true){
		$tmp = '';
		$this->_del_q = new _delete();
		$this->_del_q->_set_db_main_tbl('_app_topic_ex_q');
		$this->_del_q->_set_img_class('delete_question');
		$this->_del_q->_set_list_id('li#e');
		$this->_del_q->_set_sub_list_id('');
		$this->_del_q->_set_add_script_tags(false);
		$this->_del_q->_set_add_document_ready(false);
		if ($auto){return $this->_del_q->_delete_jq();}else{return $this->_del_q;}
	}

	private function _fetch_topic_exercises(){
		$_sql = 'select * from _app_topic_ex where topic_id = :topic_id order by order_num';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_rows;
	}

	private function _fetch_exercise_questions(){//returns an array of questions
		// Now load the exercise questions
		$_sql = 'select * from _app_topic_ex_q where topic_id = :topic_id and ex_id = :ex_id order by difficulty';
		$_d = array('topic_id' => $this->_topic_id, 'ex_id' => $this->_topic_ex_id);
		$_f = array('i', 'i');
		$_qus = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_qus;
	}

	private function _build_ex_edit_list(){
		$_params = array(
			'el_type' => 'number',
			'el_number_inc' => 1,
			'el_number_min' => 1,
			'el_number_max' => 50,
			'el_name' => 'number_of_questions',
			'el_value' => $this->_topic_ex_num_qs,
			'el_data_id' => $this->_topic_ex_id,
			'el_data_db_tbl' => '_app_topic_ex'
		);
		$_nqs = new _select($_params);
		$_num_qs_field = $_nqs->_build_select();

		$tmp = "<ul id = 'exh".$this->_topic_ex_id."' class = 'topic_exercise'>";
		$tmp .= "<li class='w100pc'><div class='ex_eg'>";

		$this->_del_ex->_set_db_tbl_field_value($this->_topic_ex_id);
		$tmp .= $this->_del_ex->_delete_img();
		$tmp .= "<div class='w32 hauto'><img class = 'open_ex point ttip' title = 'Click to open the exercise.' id = 'ao".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/closed.png' alt = 'Open' />";
		$tmp .= "<img class = 'hidden open_ex point ttip' title = 'Click to close the exercise.' id = 'ac".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/opened.png' alt = 'Close' /></div>";
		$tmp .= "<div class='ex_eg'><h3>Exercise $this->_ex_count: </h3>$this->_ex_title $_num_qs_field</div><div><button type = 'button' class = 'add_new_qu add' id = 'an".$this->_topic_ex_id."'>Add new question</button></div><div>".$this->_ex_shuffle."Shuffle</div></li></ul>";

		$tmp .= $this->_build_ex_edit_questions_list();
		return $tmp;
	}

	private function _build_ex_edit_questions_list(){
		$this->_topic_ex_qs = $this->_fetch_exercise_questions();
		$tmp = "<ul id = 'exqs".$this->_topic_ex_id."' class = 'topic_exercise hidden'>";
		$tmp .= "<li><div class='mr5 b'>Instructions:</div><div class='w90pc'>$this->_ex_instructions</div></li>";
		if (!empty($this->_topic_ex_qs)){
			$_exq_count = 0;

			do {

				$_qid = $this->_topic_ex_qs[$_exq_count]['id'];
				$this->_del_q->_set_db_tbl_field_value($_qid);
				$_q_count = $_exq_count + 1;
				$_question = $this->_topic_ex_qs[$_exq_count]['question'];
				$_answer = $this->_topic_ex_qs[$_exq_count]['answer'];
				$_question = $this->_build_text('_app_topic_ex_q', 'question', $_question, $_qid);
				$_answer = $this->_build_text('_app_topic_ex_q', 'answer', $_answer, $_qid);

				$_params = array(
					'el_type' => 'number',
					'el_number_inc' => 1,
					'el_number_min' => 1,
					'el_number_max' => 20,
					'el_name' => 'difficulty',
					'el_value' => $this->_topic_ex_qs[$_exq_count]['difficulty'],
					'el_data_id' => $_qid,
					'el_data_db_tbl' => '_app_topic_ex_q'
				);
				$_diff_sel = new _select($_params);
				$_diff_field = $_diff_sel->_build_select();

				$tmp .= "<li id = 'e".$_qid."'>";
				$tmp .= $this->_del_q->_delete_img();
				$tmp .= "<div class = 'label_e vt ml5'>Q".$_q_count."</div><div class = 'question_e'>".$_question."</div>";
				$tmp .= "<div class = 'answer_e'>".$_answer."</div>";
				$tmp .= "<div class = 'difficulty_e'>".$_diff_field."</div>";
				$tmp .= "</li>";
				$_exq_count++;
			} while (!empty($this->_topic_ex_qs[$_q_count]['id']));
		}
		$tmp .= "</ul>";
		return $tmp;
	}

	public function _build_new_exercise($topic_id, $id){
		$id = (int) $id;
		$_params = array(
			'el_type' => 'number',
			'el_number_inc' => 1,
			'el_number_min' => 1,
			'el_number_max' => 50,
			'el_name' => 'number_of_questions',
			'el_value' => 10,
			'el_data_id' => $id,
			'el_data_db_tbl' => '_app_topic_ex'
		);
		$_nqs = new _select($_params);
		$_num_qs_field = $_nqs->_build_select();

		//Find number of exercises currently
		$_sql = 'select count(id) from _app_topic_ex where topic_id = :topic_id';
		$_d = array('topic_id' => $topic_id);
		$_f = array('i');

		$_count = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);

		$this->_ex_title = $this->_build_varchar('_app_topic_ex', 'title', '', $this->_topic_ex_id);
		$this->_ex_instructions = $this->_build_varchar('_app_topic_ex', 'instructions', '', $this->_topic_ex_id);
		$this->_del_ex = $this->_build_del_ex_jq_code(false);
		$this->_del_ex->_set_db_tbl_field_value($id);
		$tmp = "<ul id = 'exh".$this->_topic_ex_id."' class = 'sortable-list topic_exercise'>";
		$tmp .= "<li>";

		$this->_del_ex->_set_db_tbl_field_value($id);
		$tmp .= $this->_del_ex->_delete_img();
		$tmp .= "<div class='w32'><img class = 'open_ex point' id = 'ao".$id."' src='".__s_lib_url__."_images/_icons/closed.png' />";
		$tmp .= "<img class = 'hidden open_ex point' id = 'ac".$id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
		$tmp .= "<h3>Exercise ".$_count.": </h3>$this->_ex_title $_num_qs_field<div><button type = 'button' class = 'add_new_qu add' id = 'an".$this->_topic_ex_id."'>Add new question</button></div></li></ul>";
		return $tmp;
	}

	public function _build_new_question($topic_id, $id){
		$id = (int) $id;
		$this->_del_q = $this->_build_del_q_jq_code(false);
		$this->_del_q->_set_db_tbl_field_value($id);
		$_question = $this->_build_text('_app_topic_ex_q', 'question', '', $id);
		$_answer = $this->_build_text('_app_topic_ex_q', 'answer', '', $id);
		$tmp = "<li id = 'e".$id."'>";
		$tmp .= $this->_del_q->_delete_img();
		$tmp .= "<div class = 'label vt ml5'>New question</div><div class = 'question'>".$_question."</div>";
		$tmp .= "<div class = 'answer'>".$_answer."</div>";
		$tmp .= "</li>";
		return $tmp;
	}

	private function _build_add_new_question_jq(){
		$tmp = "$(document).on('click', '.add_new_qu', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
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
						url: '".__s_app_url__."_ajax/_add_question.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#exqs'+id).append(data);
						}
					});
				});";
		return $tmp;
	}
	private function _build_add_new_exercise_jq(){
		$tmp = "$(document).on('click', '.add_new_ex', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_ex');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_exercise.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#exercises').append(data);
						}
					});
				});";
		return $tmp;
	}

	public function _set_topic_id($_t){$this->_topic_id = $_t;}
	public function _set_del_ex($_t) { $this->_del_ex = $_t; }
	public function _set_del_q($_t) { $this->_del_q = $_t; }
	public function _set_topic_ex_id($_t) { $this->_topic_ex_id = $_t; }
	public function _set_make_ex_tab($_t) { $this->_make_ex_tab = $_t; }
	public function _get_make_ex_tab() { return $this->_make_ex_tab; }
}
?>