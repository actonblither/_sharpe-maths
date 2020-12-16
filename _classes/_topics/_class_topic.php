<?php
class _topic extends _setup{

	private $_topic_id;
	private $_topic_route_id;
	private $_topic_title_bar;
	private $_topic_tab_bar;

	private $_topic_title_bar_img;
	private $_topic_tab_bar_path;
	private $_topic_title;
	private $_topic_intro;

	private $_topic_ex;
	private $_topic_eg;
	private $_topic_pz;
	private $_topic_q_id;
	private $_topic_q;
	private $_topic_ex_q;
	private $_topic_ex_id;
	private $_topic_ex_num_qs;
	private $_topic_ex_qs;

	private $_del_ex;
	private $_del_q;
	private $_ex_count;
	private $_ex_title;
	private $_ex_instructions;

	public function __construct($auto = true){
		parent::__construct();
		$this->_dbh = new _db();
		$this->_topic_route_id = rvz($_REQUEST['id']);
		if ($auto){echo $this->_build_topic();}
	}

	public function _build_topic(){
		// Fill the _list_form_container with access to the clicked upon topic
		// 1. TITLE BAR
		// 2. MENU TABS
		$this->_fetch_topic_id();
		$this->_load_topic_data();
		$tmp = $this->_build_title_bar();
		$tmp .= $this->_build_tab_bar();

		return $tmp;
	}

	private function _load_topic_data(){
		$_sql = 'select * from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);

		$this->_set_topic_title($_row['title']);
		$this->_set_topic_intro($_row['intro']);
		$this->_set_topic_title_bar_img($_row['title_bar_img']);
	}

	private function _fetch_topic_id(){
		$_sql = 'select id from _app_topic where route_id = :route_id';
		$_d = array('route_id' => $this->_get_topic_route_id());
		$_f = array('i');
		$this->_set_topic_id($this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f));
	}


	private function _build_title_bar(){
		$_tb = new _title_bar();
		$_tb->_set_img($this->_get_topic_title_bar_img());
		$_tb->_set_title($this->_get_topic_title());
		return $_tb->_build_title_bar();
	}



	private function _build_tab_bar(){
		$this->_build_intro_text();
		$this->_build_examples();
		$this->_build_exercises();
		$this->_build_puzzles();

		$_tab = new _tabs();
		$_tab->_set_tab_nav_id('top-tabs');
		$_tab->_set_tab_labels(array('Indroduction', 'Worked examples', 'Exercises', 'Puzzles'));
		$_tab->_set_tab_links(array('intro-1', 'example-2', 'exercise-3', 'puzzles-4'));
		$_tab->_set_tab_help(array('', '', '', ''));
		$_tab->_set_tab_pages(array($this->_topic_intro, $this->_topic_eg, $this->_topic_ex, $this->_topic_pz));

		return $_tab->_build_all();
	}

	private function _build_puzzles(){
		$_pz = new _puzzle();
		$this->_topic_pz = $_pz->_fetch_topic_puzzles($this->_topic_id);
	}

	private function _build_intro_text(){
		$_sql = 'select intro from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$this->_topic_intro = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		if (is_logged_in()){
			$this->_build_intro_edit();
		}
	}

	private function _build_intro_edit(){
		$_el = new _form_element();
		$_el->_set_el_field_id('intro');
		$_el->_set_el_field_value($this->_topic_intro);
		$_el->_set_db_tbl('_app_topic');
		$_el->_set_el_id_value($this->_topic_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(400);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('px');
		$this->_topic_intro = $_el->_build_textarea();

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('_app_topic');
		$_el_btn->_set_el_id_value($this->_topic_id);
		$_el_btn->_set_el_field_id('intro');
		$_el_btn->_set_el_field_value('Save topic introduction');
		$_el_btn->_set_el_width(200);
		$_el_btn->_set_el_width_units('px');
		$_el_btn->_build_save_btn();

		$this->_topic_intro .= $_el_btn->_build_save_btn();
	}

	private function _build_examples(){
		$_sql = 'select * from _app_topic_eg where topic_id = :topic_id order by order_num, id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);

		$tmp = "<script>$(document).ready(function(e){";

		if (is_logged_in()){
			$tmp .= $this->_build_jq_sort_code();
			$tmp .= $this->_build_add_new_example_jq();
		}
		$tmp .= "});</script>";
		$_count = 1;
		if (is_logged_in()){
			$tmp .= $this->_build_add_new_example_btn();
			$tmp .= "<ul id='examples'><li>";
		}


		if (!empty($_rows)){
			foreach ($_rows as $_row){
				$_id = $_row['id'];
				$tmp .= "<ul id='egh".$_id."' class='sortable-list topic_example'>";

				if (is_logged_in()){
					$this->_ex_title = $this->_build_varchar('_app_topic_eg', 'title', $_row['title'], $_id);
					$_method = $this->_build_text('_app_topic_eg', 'method', $_row['method'], $_id);
					$_method_note = $this->_build_text('_app_topic_eg', 'method_note', $_row['method_note'], $_id);
					$_question = $this->_build_text('_app_topic_eg', 'question', $_row['question'], $_id);
					$_question_note = $this->_build_text('_app_topic_eg', 'question_note', $_row['question_note'], $_id);
					$_answer = $this->_build_text('_app_topic_eg', 'answer', $_row['answer'], $_id);
					$_answer_note = $this->_build_text('_app_topic_eg', 'answer_note', $_row['answer_note'], $_id);
				}else{
					$this->_ex_title = $_row['title'];
					$_method = $_row['method'];
					$_method_note = $_row['method_note'];
					$_question = $_row['question'];
					$_question_note = $_row['question_note'];
					$_answer = $_row['answer'];
					$_answer_note = $_row['answer_note'];
				}

				//First build the table containing the example
				if (is_logged_in()){
					$tmp .= "<li id='nl".$_id."' data-db_tbl='_app_topic_eg'>";
				}else{
					$tmp .= "<li class='w100pc'>";
				}
				$tmp .= "<div id = 't".$_id."' class = 'ex_eg w100pc'>";
				$tmp .= "<div class='w20'><img alt = 'Open' title='Click to open the example.' id = 'io".$_id."' class = 'open_eg point ttip' src='".__s_lib_url__."_images/_icons/open.png' />";
				$tmp .= "<img alt = 'Close' title='Click to close the example.' class = 'hidden open_eg point ttip' id = 'ic".$_id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
				if (is_logged_in()){
					$tmp .= "<div class='row w90pc'><h3>WE $_count:</h3> $this->_ex_title</div>";
				}else{
					$tmp .= "<div class='row w70pc'><h3>Worked example $_count:  $this->_ex_title</h3></div>";
				}

				$tmp .= "</li></ul>";

				$tmp .= "<ul id = 'egqs".$_id."' class = 'topic_example hidden'>
				<li class='top nb'><div class='label'></div><div class='text'></div><div class='note b'>Notes</div></li>";
				$tmp .= $this->_build_example_li("<img class='block20 ttip' title = 'Question' alt = 'Question' src = './_images/_icons/question20.png' />", $_question, $_question_note);
				$tmp .= $this->_build_example_li("<img class='block20 ttip' title = 'Method' alt = 'Method' src = './_images/_icons/method20.png' />", $_method, $_method_note);
				$tmp .= $this->_build_example_li("<img class='block20 ttip' title = 'Answer' alt = 'Answer' src = './_images/_icons/answer20.png' />", $_answer, $_answer_note);



				$tmp .= "</ul>";
				$_count++;
			}
		}
		if (is_logged_in()){
			$tmp .= "</li></ul>";
		}
		$this->_topic_eg = $tmp;
	}

	private function _build_example_li($_l, $_t, $_n){
		return "<li><div class='label'>".$_l."</div><div class='text'>".$_t."</div><div class='note'>".$_n."</div></li>";
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

		if (is_logged_in()){
			$tmp .= $this->_build_add_new_exercise_btn();
			$tmp .= "<ul id='exercises'><li>";
		}
		$this->_ex_count = 1;
		if (!empty($_rows)){
			foreach ($_rows as $_row){
				$tmp .= $this->_build_exercise($_row);
				$this->_ex_count++;
			}
		}
		$tmp .= "</li></ul>";
		$this->_topic_ex = $tmp;
	}

	private function _build_exercise($_row){
		$this->_topic_ex_id = $_row['id'];
		$this->_topic_ex_num_qs = $_row['number_of_questions'];
		$this->_ex_title = $_row['title'];
		$this->_ex_instructions = $_row['instructions'];
		if (is_logged_in()){
			$this->_ex_title = $this->_build_varchar('_app_topic_ex', 'title', $this->_ex_title, $this->_topic_ex_id);
			$this->_ex_instructions = $this->_build_text('_app_topic_ex', 'instructions', $this->_ex_instructions, $this->_topic_ex_id);
			return $this->_build_ex_edit_list();
		}else{
			return $this->_build_ex_view_list();
		}
	}

	private function _build_ex_edit_list(){
		$_params = array(
			'el_type' => 'number',
			'el_number_inc' => 1,
			'el_number_min' => 1,
			'el_number_max' => 20,
			'el_name' => 'number_of_questions',
			'el_value' => $this->_topic_ex_num_qs,
			'el_data_id' => $this->_topic_ex_id,
			'el_data_db_tbl' => '_app_topic_ex'
		);
		$_nqs = new _select($_params);
		$_num_qs_field = $_nqs->_build_select();


		$tmp = "<ul id = 'exh".$this->_topic_ex_id."' class = 'sortable-list topic_example'>";
		$tmp .= "<li class='w100pc'><div class='ex_eg'>";

		$this->_del_ex->_set_db_tbl_field_value($this->_topic_ex_id);
		$tmp .= $this->_del_ex->_delete_img();
		$tmp .= "<div class='w20 hauto'><img class = 'open_ex point ttip' title = 'Click to open the exercise.' id = 'ao".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/open.png' alt = 'Open' />";
		$tmp .= "<img class = 'hidden open_ex point ttip' title = 'Click to close the exercise.' id = 'ac".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/close.png' alt = 'Close' /></div>";
		$tmp .= "<div class='ex_eg'><h3>Exercise $this->_ex_count: </h3>$this->_ex_title $_num_qs_field</div><div><button type = 'button' class = 'add_new_qu add' id = 'an".$this->_topic_ex_id."'>Add new question</button></div></li>";
		$tmp .= "<li><div class='mr5 b'>Instructions:</div><div class='w90pc'>$this->_ex_instructions</div></li></ul>";
		$tmp .= $this->_build_ex_edit_questions_list();
		return $tmp;
	}

	private function _build_ex_edit_questions_list(){
		$this->_topic_ex_qs = $this->_fetch_exercise_questions();
		$tmp = "<ul id = 'exqs".$this->_topic_ex_id."' class = 'topic_exercise hidden'>";
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
				$tmp .= "<li id = 'e".$_qid."'>";
				$tmp .= $this->_del_q->_delete_img();
				$tmp .= "<div class = 'label vt ml5'>Q".$_q_count."</div><div class = 'question'>".$_question."</div>";
				$tmp .= "<div class = 'answer'>".$_answer."</div>";
				$tmp .= "</li>";
				$_exq_count++;
			} while (!empty($this->_topic_ex_qs[$_q_count]['id']));
		}
		$tmp .= '</ul>';
		return $tmp;
	}

	public function _build_new_exercise($topic_id, $id){
		$id = (int) $id;
		$_params = array(
			'el_type' => 'number',
			'el_number_inc' => 1,
			'el_number_min' => 1,
			'el_number_max' => 20,
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
		$tmp .= "<div class='w20'><img class = 'open_ex point' id = 'ao".$id."' src='".__s_lib_url__."_images/_icons/open.png' />";
		$tmp .= "<img class = 'hidden open_ex point' id = 'ac".$id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
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

	private function _build_ex_view_list(){

		$tmp = "<ul id = 'exh".$this->_topic_ex_id."' class = 'topic_exercise'>";
		$tmp .= "<li class='w100pc'><div class='ex_eg w100pc'>";
		$tmp .= "<div class='w20 hauto'><img title = 'Click to open exercise.' alt = 'Open' class = 'open_ex point ttip' id = 'ao".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/open.png' />";
		$tmp .= "<img title = 'Click to close exercise.' alt = 'Close' class = 'hidden open_ex point ttip' id = 'ac".$this->_topic_ex_id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
		$tmp .= "<h3>Exercise $this->_ex_count: $this->_ex_title</h3></div></li>";
		if (!empty($this->_ex_instructions)){
			$tmp .= "<li><div class='b mr10'>Instructions:</div><div>$this->_ex_instructions</div></li>";
		}

		$tmp .= "</ul>";

		$tmp .= $this->_build_ex_view_questions_list();
		return $tmp;
	}

	private function _build_ex_view_questions_list(){
		// Now load the exercise questions
		$this->_topic_ex_qs = $this->_fetch_exercise_questions();
		//shuffle($this->_topic_ex_qs);
		$tmp = "<ul id = 'exqs".$this->_topic_ex_id."' class = 'topic_exercise hidden'>";
		if (!empty($this->_topic_ex_qs)){
			$_exq_count = 0;
			do {
				$_qid = $this->_topic_ex_qs[$_exq_count]['id'];
				$_q_count = $_exq_count + 1;
				$_question = $this->_topic_ex_qs[$_exq_count]['question'];
				$_answer = $this->_topic_ex_qs[$_exq_count]['answer'];
				$tmp .= "<li id = 'e".$_qid."'>";
				$tmp .= "<div class = 'label vt r b w30'>Q".$_q_count."</div>
					<div class = 'question'>".$_question."</div>";
				$tmp .= "
				<div class = 'answer r vt mr5 eye' id = 'visible".$_qid."'>
					<div class='w32'><img class = 'point' src = '_stdlib/_images/_icons/answer.png' /></div>
				</div>

				<div class = 'eye_store hidden' id = 'eye_store".$_qid."'>
					<div class='w32'><img class = 'point' src = '_stdlib/_images/_icons/answer.png' /></div>
				</div>
				<div class = 'ans_store hidden' id = 'ans_store".$_qid."'>
					<span class = 'point mr5'>".$_answer."</span>
				</div>";

				$tmp .= "</li>";
				$_exq_count++;
			} while ($_exq_count < $this->_topic_ex_num_qs && !empty($this->_topic_ex_qs[$_q_count]['id']));
		}
		$tmp .= "</ul></li></ul>";
		return $tmp;
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

	private function _build_add_new_example_btn(){
		$tmp = "<button type = 'button' class = 'add_new_eg add w200 mt5 ml10' id = 'to".$this->_topic_id."'>Add new worked example</button>";
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
		$_sql = 'select * from _app_topic_ex where topic_id = :topic_id';
		$_d = array('topic_id' => $this->_get_topic_id());
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_rows;
	}

	private function _fetch_exercise_questions(){//returns an array of questions
		// Now load the exercise questions
		$_sql = 'select * from _app_topic_ex_q where topic_id = :topic_id and ex_id = :ex_id';
		$_d = array('topic_id' => $this->_topic_id, 'ex_id' => $this->_topic_ex_id);
		$_f = array('i', 'i');
		$_qus = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $_qus;
	}

	private function _build_jq_sort_code(){
		$tmp = "$('.sortable-list').sortable({
			items: 'li',
			update: function(event, ui) {
				var new_list = $(this).sortable('toArray').toString();
				var db_tbl = $(this.firstChild.nextSibling).attr('data-db_tbl');
				var fd = new FormData();
				fd.set('nlist', new_list);
				fd.set('gen_table', db_tbl);
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
		});";
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

	private function _build_add_new_example_jq(){
		$tmp = "$(document).on('click', '.add_new_eg', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(2);
					var fd = new FormData();
					fd.append('db_tbl', '_app_topic_eg');
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('topic_id', ".$this->_topic_id.");
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_app_url__."_ajax/_add_example.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#examples').append(data);
						}
					});
				});";
		return $tmp;
	}

	public function _build_new_example($id){
		$_id = (int) $id;
		$this->_ex_title = $this->_build_varchar('_app_topic_eg', 'title', '', $_id);
		$_method = $this->_build_text('_app_topic_eg', 'method','', $_id);
		$_method_note = $this->_build_text('_app_topic_eg', 'method_note', '', $_id);
		$_question = $this->_build_text('_app_topic_eg', 'question', '', $_id);
		$_question_note = $this->_build_text('_app_topic_eg', 'question_note', '', $_id);
		$_answer = $this->_build_text('_app_topic_eg', 'answer','', $_id);
		$_answer_note = $this->_build_text('_app_topic_eg', 'answer', '', $_id);


		//First build the table containing the example
		$tmp .= "<div id = 't".$_id."' class = 'ex_eg w80pc'>";
		$tmp .= "<div class='w20'><img id = 'io".$_id."' class = 'open_ex point' src='".__s_lib_url__."_images/_icons/open.png' />";
		$tmp .= "<img class = 'hidden open_ex point' id = 'ic".$_id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
		$tmp .= "<div><h3>New example:</h3></div> $this->_ex_title</div>";

		$tmp .= "<ul id = 'exqs".$_id."' class = 'topic_example hidden'>
		<li><div class='label'></div><div class='text'></div><div class='text'><strong>Notes</strong></div></li>
		<li><div class='label'><img src = './_images/_icons/question20.png' /></div><div class='text'>$_question</div><div class='note'>$_question_note</div></li>
		<li><div class='label'><img src = './_images/_icons/method20.png' /></div><div class='text'>$_method</div><div class='note'>$_method_note</div></li>
		<li><div class='label'><img src = './_images/_icons/answer20.png' /></div><div class='text'>$_answer</div><div class='note'>$_answer_note</div></li>
		</ul>";
		return $tmp;
	}


	public function _get_topic_id() { return $this->_topic_id; }
	public function _get_topic_route_id() { return $this->_topic_route_id; }
	public function _get_topic_title_bar() { return $this->_topic_title_bar; }
	public function _get_topic_tab_bar() { return $this->_topic_tab_bar; }
	public function _get_topic_title_bar_img() { return $this->_topic_title_bar_img; }
	public function _get_topic_tab_bar_path() { return $this->_topic_tab_bar_path; }
	public function _get_topic_title() { return $this->_topic_title; }
	public function _get_topic_intro() { return $this->_topic_intro; }
	public function _get_topic_ex() { return $this->_topic_ex; }
	public function _get_topic_eg() { return $this->_topic_eg; }
	public function _get_topic_pz() { return $this->_topic_pz; }
	public function _get_topic_q_id() { return $this->_topic_q_id; }
	public function _get_topic_q() { return $this->_topic_q; }
	public function _get_topic_ex_q() { return $this->_topic_ex_q; }
	public function _get_topic_ex_id() { return $this->_topic_ex_id; }
	public function _get_topic_ex_num_qs() { return $this->_topic_ex_num_qs; }
	public function _get_topic_ex_qs() { return $this->_topic_ex_qs; }
	public function _get_del_ex() { return $this->_del_ex; }
	public function _get_del_q() { return $this->_del_q; }
	public function _get_ex_count() { return $this->_ex_count; }
	public function _get_ex_title() { return $this->_ex_title; }
	public function _get_ex_instructions() { return $this->_ex_instructions; }

	public function _set_topic_id($_t) { $this->_topic_id = $_t; }
	public function _set_topic_route_id($_t) { $this->_topic_route_id = $_t; }
	public function _set_topic_title_bar($_t) { $this->_topic_title_bar = $_t; }
	public function _set_topic_tab_bar($_t) { $this->_topic_tab_bar = $_t; }
	public function _set_topic_title_bar_img($_t) { $this->_topic_title_bar_img = $_t; }
	public function _set_topic_tab_bar_path($_t) { $this->_topic_tab_bar_path = $_t; }
	public function _set_topic_title($_t) { $this->_topic_title = $_t; }
	public function _set_topic_intro($_t) { $this->_topic_intro = $_t; }
	public function _set_topic_ex($_t) { $this->_topic_ex = $_t; }
	public function _set_topic_eg($_t) { $this->_topic_eg = $_t; }
	public function _set_topic_pz($_t) { $this->_topic_pz = $_t; }
	public function _set_topic_q_id($_t) { $this->_topic_q_id = $_t; }
	public function _set_topic_q($_t) { $this->_topic_q = $_t; }
	public function _set_topic_ex_q($_t) { $this->_topic_ex_q = $_t; }
	public function _set_topic_ex_id($_t) { $this->_topic_ex_id = $_t; }
	public function _set_topic_ex_num_qs($_t) { $this->_topic_ex_num_qs = $_t; }
	public function _set_topic_ex_qs($_t) { $this->_topic_ex_qs = $_t; }
	public function _set_del_ex($_t) { $this->_del_ex = $_t; }
	public function _set_del_q($_t) { $this->_del_q = $_t; }
	public function _set_ex_count($_t) { $this->_ex_count = $_t; }
	public function _set_ex_title($_t) { $this->_ex_title = $_t; }
	public function _set_ex_instructions($_t) { $this->_ex_instructions = $_t; }
}
?>