<?php
class _exercise extends _topic_tab{
	protected $_main_db_tbl = '_app_topic_ex';
	protected $_sub_db_tbls = array('_app_topic_ex_q');
	protected $_parent_list_id = 'exercises';
	protected $_head_list_id = 'exh';
	protected $_sub_list_id = 'exi';
	protected $_item_name = 'exercise';
	protected $_item_class = '_exercise';
	protected $_item_sql;
	protected $_del_img_class = 'del_m_ex';
	protected $_del_img_item_class = 'del_s_ex_q';
	protected $_title_prefix = 'Exercise';
	protected $_title_field_name = 'tex_title';
	protected $_field_prefix = 'tex_';
	protected $_open_close_id_prefix = 'x';

	protected $_topic_ex_id;
	protected $_topic_ex_num_qs;

	protected $_head_elements = true;
	protected $_sub_instructions = true;
	protected $_sub_body = false;
	protected $_sortable_list_prefix = 'nex';

	public function __construct($_tid){
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;

		$this->_is_logged_in = is_logged_in();
		if ($this->_is_logged_in){
			$this->_tpl_sub = __s_app_folder__.'_classes/_templates/_admin_exercise_sub_tpl.txt';
		}else{
			$this->_tpl_sub = __s_app_folder__.'_classes/_templates/_user_exercise_sub_tpl.txt';
		}

		$this->_del_params = array(
			'main_db_tbl' => $this->_main_db_tbl,
			'sub_db_tbls' => $this->_sub_db_tbls,
			'sub_db_tbl_fields' => array('ex_id'),
			'image_class' => $this->_del_img_class,
			'add_script_tags' => false,
			'add_document_ready' => false,
			'head_list_id' => $this->_head_list_id,
			'sub_list_id' => $this->_sub_list_id
		);

		$this->_del_item_params = array(
			'main_db_tbl' => '_app_topic_ex_q',
			'image_class' => $this->_del_img_item_class,
			'add_script_tags' => false,
			'add_document_ready' => false,
			'head_list_id' => $this->_head_list_id,
			'sub_list_id' => $this->_sub_list_id
		);

		$this->_sub_sql = true;
		$this->_sub_instructions = true;
		$this->_sub_body = false;
		$this->_head_elements = true;

		$this->_build_items();
	}

	public function _fetch_template($_tpl, $_r = array()){
		if (isset($_r['topic_id'])){$this->_topic_id = $_r['topic_id'];}
		$this->_sr = array(
				'_title_field_name' => $this->_title_field_name,
				'_title_prefix' => $this->_title_prefix,
				'_parent_list_id' => $this->_parent_list_id,
				'_head_list_id' => $this->_head_list_id,
				'_sub_list_id' => $this->_sub_list_id,
				'_open_close_id_prefix' => $this->_open_close_id_prefix,
				'_main_db_tbl' => $this->_main_db_tbl,
				'_item_id' => $this->_item_id,
				'_topic_id' => $this->_topic_id,
				'_item_count' => $this->_item_count,
				'_list_count' => $this->_list_count,
				'_del_class' => $this->_del_img_class,
				'_icon_lib_url' => __s_lib_icon_url__,
				'_icon_app_url' => __s_app_icon_url__,
				'_occ_class' => $this->_occ_class,
				'_oco_class' => $this->_oco_class,
				'_item_title' => rvs($_r['tex_title']),
				'_item_name' => $this->_item_name,
				'_item_detail_id' => rvz($_r['id']),
				'_question' => rvs($_r['question']),
				'_answer' => rvs($_r['answer']),
				'_field_prefix' => $this->_field_prefix,
				'_sortable_list_prefix' => $this->_sortable_list_prefix
		);

		if (!empty($_r['tex_instructions'])){
			$this->_sr['_instructions'] = rvs($_r['tex_instructions']);
		}
		return $this->_fetch_template_file($_tpl);
	}

	protected function _fetch_sub_list($_ex){
		$this->_topic_ex_id = $_ex['id'];
		$this->_topic_ex_num_qs = rvz($_ex['number_of_questions']);
		if (empty($_ex['tex_instructions']) && !$this->_is_logged_in){$this->_sub_instructions = false;}
		if ($_ex['tex_shuffle'] && !$this->_is_logged_in){
			$this->_q_rows = $this->_pick_rnd_questions_by_difficulty();
		}else{
			$this->_q_rows = $this->_fetch_exercise_questions();
		}
		$_question_rows = '';
		$_q_count = 1;
		foreach ($this->_q_rows as $_qs){
			$_id = $_qs['id'];
			$this->_item_count = $_q_count;
			$_question_rows .= $this->_fetch_template($this->_tpl_sub, $_qs);
			$_q_count++;
		}
		return $_question_rows;
	}

	private function _pick_rnd_questions_by_difficulty(){
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

	protected function _build_edit_elements($_ex){
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
		if ($this->_is_logged_in){
			$_params = array(
					'db_tbl' => '_app_topic_ex',
					'field_name' => 'tex_shuffle',
					'field_value' => $_ex_shuffle,
					'id' => $_id,
			);
			$_ex_shuffle = _build_checkbox($_params);
		}
		$_ret = $this->_build_add_new_question_jq();
		$_ret .= "<div class='row center'>".$this->_build_add_btn($_id);
		$_ret .= "<label for='tex_shuffle_".$_id."'> &nbsp;&nbsp; Shuffle: </label>".$_ex_shuffle;
		$_ret .= "<label for='number_of_questions_".$_id."'> &nbsp;&nbsp; Num qs: </label>".$_num_qs_field."</div>";
		return $_ret;
	}

	private function _build_add_new_question_jq(){
		$tmp = "<script>
		$(document).ready(function(){
			$(document).on('click', '.add_new_qu', function(e){
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
							$('ul#".$this->_sub_list_id."'+id).append(data);
						}
					});});
				});</script>";
		return $tmp;
	}

	private function _build_add_btn($_id){
		$_tmp = "<button type = 'button' class = 'w140 add_new_qu add' id = 'anq".$_id."'>Add new question</button>";
		return $_tmp;
	}
}
?>