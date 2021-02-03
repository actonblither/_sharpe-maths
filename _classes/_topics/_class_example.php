<?php
class _example{

	private $_dbh;
	private $_examples;
	private $_eg_title;
	private $_make_eg_tab = false;
	private $_rows;
	private $_topic_id;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$_sql = 'select * from _app_topic_eg where topic_id = :topic_id order by order_num, id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($this->_rows) || is_logged_in()){$this->_make_eg_tab = true;}
	}

	public function _fetch_examples(){
		return $this->_build_examples();
	}

	private function _build_examples(){
		$tmp = "";
		if (is_logged_in()){
			$tmp .= "<script>$(document).ready(function(e){";
			$tmp .= $this->_build_add_new_example_jq();
			$tmp .= "});</script>";
		}

		$_count = 1;
		if (is_logged_in()){
			$tmp .= $this->_build_add_new_example_btn();
			$tmp .= "<ul id='examples' class='sortable-list'>";
		}else{
			$tmp .= "<ul id='examples' class = 'flist'>";
		}

		if (!empty($this->_rows)){
			foreach ($this->_rows as $_row){
				$_id = $_row['id'];
				$tmp .= "<li id = 'n".$_id."' data-db-tbl='_app_topic_eg' class='ex'>";
				$tmp .= "<ul id = 'egh".$_id."' class = 'topic_example'>";

				if (is_logged_in()){
					$this->_eg_title = $this->_build_varchar('_app_topic_eg', 'teg_title', $_row['teg_title'], $_id);
					$_method = $this->_build_text('_app_topic_eg', 'teg_method', $_row['teg_method'], $_id);
					$_method_note = $this->_build_text('_app_topic_eg', 'teg_method_note', $_row['teg_method_note'], $_id);
					$_question = $this->_build_text('_app_topic_eg', 'teg_question', $_row['teg_question'], $_id);
					$_question_note = $this->_build_text('_app_topic_eg', 'teg_question_note', $_row['teg_question_note'], $_id);
					$_answer = $this->_build_text('_app_topic_eg', 'teg_answer', $_row['teg_answer'], $_id);
					$_answer_note = $this->_build_text('_app_topic_eg', 'teg_answer_note', $_row['teg_answer_note'], $_id);
				}else{
					$this->_eg_title = $_row['teg_title'];
					$_method = $_row['teg_method'];
					$_method_note = $_row['teg_method_note'];
					$_question = $_row['teg_question'];
					$_question_note = $_row['teg_question_note'];
					$_answer = $_row['teg_answer'];
					$_answer_note = $_row['teg_answer_note'];
				}

				//First build the table containing the example
				if (is_logged_in()){
					$tmp .= "<li id = 'il".$_id."'>";
				}else{
					$tmp .= "<li id = 'il".$_id."' class='w100pc point open_eg'>";
				}
				$tmp .= "<div id = 't".$_id."' class = 'ex_eg w100pc'>";
				$tmp .= "<div class = 'w32'><img alt = 'Open' title='Click to open the example.' id = 'io".$_id."' class = 'open_eg point ttip' src='".__s_lib_url__."_images/_icons/closed.png' />";
				$tmp .= "<img alt = 'Close' title='Click to close the example.' class = 'hidden open_eg point ttip' id = 'ic".$_id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
				if (is_logged_in()){
					$tmp .= "<div class='row w90pc'><h3>WE $_count:</h3> $this->_eg_title</div>";
				}else{
					$tmp .= "<div class='row w70pc'><h3>Worked example $_count:  $this->_eg_title</h3></div>";
				}

				$tmp .= "</li></ul>";

				$tmp .= "<ul id = 'egqs".$_id."' class = 'topic_example hidden'>
				<li class='top nb'><div class='label'></div><div class='text'></div><div class='note b'>Notes</div></li>";
				$tmp .= $this->_build_example_li("<img class='block32 ttip' title = 'Problem' alt = 'Problem' src = './_images/_icons/problem32.png' />", $_question, $_question_note);
				$tmp .= $this->_build_example_li("<img class='block32 ttip' title = 'Method' alt = 'Method' src = './_images/_icons/method32.png' />", $_method, $_method_note);
				$tmp .= $this->_build_example_li("<img class='block32 ttip' title = 'Solution' alt = 'Solution' src = './_images/_icons/solution32.png' />", $_answer, $_answer_note);
				$tmp .= "</ul></li>";
				$_count++;
			}
		}
		$tmp .= "</ul>";
		$this->_topic_eg = $tmp;
		return $this->_topic_eg;
	}

	private function _build_example_li($_l, $_t, $_n){
		return "<li><div class='label'>".$_l."</div><div class='text'>".$_t."</div><div class='note'>".$_n."</div></li>";
	}

	private function _build_add_new_example_btn(){
		$tmp = "<button type = 'button' class = 'add_new_eg add w200 mt5 ml10' id = 'to".$this->_topic_id."'>Add new worked example</button>";
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
		$this->_eg_title = $this->_build_varchar('_app_topic_eg', 'teg_title', '', $_id);
		$_method = $this->_build_text('_app_topic_eg', 'teg_method','', $_id);
		$_method_note = $this->_build_text('_app_topic_eg', 'teg_method_note', '', $_id);
		$_question = $this->_build_text('_app_topic_eg', 'teg_question', '', $_id);
		$_question_note = $this->_build_text('_app_topic_eg', 'teg_question_note', '', $_id);
		$_answer = $this->_build_text('_app_topic_eg', 'teg_answer','', $_id);
		$_answer_note = $this->_build_text('_app_topic_eg', 'teg_answer', '', $_id);


		//First build the table containing the example
		$tmp .= "<div id = 't".$_id."' class = 'ex_eg w80pc'>";
		$tmp .= "<div class='w32'><img id = 'io".$_id."' class = 'open_eg point' src='".__s_lib_url__."_images/_icons/closed.png' />";
		$tmp .= "<img class = 'hidden open_eg point' id = 'ic".$_id."' src='".__s_lib_url__."_images/_icons/opened.png' /></div>";
		$tmp .= "<div><h3>New example:</h3></div> $this->_eg_title</div>";

		$tmp .= "<ul id = 'exqs".$_id."' class = 'topic_example hidden'>
		<li><div class='label'></div><div class='text'></div><div class='text'><strong>Notes</strong></div></li>
		<li><div class='label'><img src = './_images/_icons/question20.png' /></div><div class='text'>$_question</div><div class='note'>$_question_note</div></li>
		<li><div class='label'><img src = './_images/_icons/method20.png' /></div><div class='text'>$_method</div><div class='note'>$_method_note</div></li>
		<li><div class='label'><img src = './_images/_icons/answer20.png' /></div><div class='text'>$_answer</div><div class='note'>$_answer_note</div></li>
		</ul>";
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