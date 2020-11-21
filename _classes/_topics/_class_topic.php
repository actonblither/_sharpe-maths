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
	private $_topic_q_id;
	private $_topic_q;
	private $_topic_ex_q;
	private $_topic_ex_id;

	public function __construct(){
		parent::__construct();
		$this->_dbh = new _db();
		$this->_topic_route_id = rvz($_REQUEST['id']);
		echo $this->_build_topic();
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
		$this->_set_topic_intro($_row['intro_body']);
		$this->_set_topic_title_bar_img($_row['title_bar_img']);
	}

	private function _fetch_topic_id(){
		$_sql = 'select id from _app_topic where route_id = :route_id';
		$_d = array('route_id' => $this->_get_topic_route_id());
		$_f = array('i');
		$this->_set_topic_id($this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f));
	}

	private function _build_title_bar(){
		$tmp = "<div class = 'page-title list-title'>
		<img src = '".__s_app_url__."_images/_title_bar_img/".$this->_get_topic_title_bar_img()."' alt = '".ucwords($this->_get_topic_title())."' />";
		// use html_entity_decode so that ampersands et al appear correctly.
		$title = html_entity_decode($this->_get_topic_title());
		$tmp .= $title . '</div>';
		return $tmp;
	}

	private function _build_tab_bar(){
		$this->_topic_intro = $this->_build_intro_text();
		$this->_topic_eg = $this->_build_examples();
		$this->_topic_ex = $this->_build_exercises();

		$_tab = new _tabs();
		$_tab->_set_tab_nav_id('top-tabs');
		$_tab->_set_tab_labels(array('Indroduction', 'Examples', 'Exercises'));
		$_tab->_set_tab_links(array('intro-1', 'example-3', 'exercise-4'));
		$_tab->_set_tab_help(array('', '', '', ''));
		$_tab->_set_tab_pages(array($this->_topic_intro, $this->_topic_eg, $this->_topic_ex));
		return $_tab->_build_all();
	}

	private function _build_intro_text(){
		$_sql = 'select intro_header, intro_body from _app_topic where id = :id';
		$_d = array('id' => $this->_topic_id);
		$_f = array('i');
		$_row = $this->_dbh->_fetch_db_row_p($_sql, $_d, $_f);
		$_intro_head = _format_header($_row['intro_header']);
		$_intro_body = $_row['intro_body'];
		$_intro = $_intro_head.$_intro_body;
		return $_intro;
	}


	private function _build_exercises(){
		$_sql = 'select * from _app_topic_ex where topic_id = :topic_id';
			$_d = array('topic_id' => $this->_get_topic_id());
			$_f = array('i');
			$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);

			$tmp = "
			<script>
				$(document).ready(function(){
					$(document).on('click', '.open', function(){
						var id = $(this).attr('id').substring(1);
						if ($('#b'+id).hasClass('hidden')){
							$('#b'+id).removeClass('hidden');
							$('#ac'+id).removeClass('hidden');
							$('#ao'+id).addClass('hidden');
						}else{
							$('#b'+id).addClass('hidden');
							$('#ao'+id).removeClass('hidden');
							$('#ac'+id).addClass('hidden');
						}
					});
					$(document).on('click', '.answer', function(){
						var id = $(this).attr('id').substring(3);
						if ($('#ans'+id).hasClass('hidden')){
							$('#ans'+id).removeClass('hidden');
							$('#eye'+id).addClass('hidden');
						}else{
							$('#ans'+id).addClass('hidden');
							$('#eye'+id).removeClass('hidden');
						}
					});
				});
			</script>";
			$_ex_count = 1;
			if (!empty($_rows)){
				foreach ($_rows as $_row){
					$_id = $_row['id'];
					$_num_qs = $_row['number_of_questions'];
					$_title = $_row['title'];

					//First build the table containing the example
					$tmp .= "<div id = 'v".$_id."' class = 'green open point'>";
					$tmp .= "<div><img id = 'ao".$_id."' src='".__s_lib_url__."_images/_icons/open.png' /></div>";
					$tmp .= "<div><img class = 'hidden' id = 'ac".$_id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
					$tmp .= "<h3>Exercise $_ex_count: $_title</h3></div>
					<ul id = 'b".$_id."' class = 'topic_exercise hidden'>
						<li class= 'nb'>
							<div class='label vm ml5'></div>
							<div class='question'></div>
							<div class='eye'><strong>Answers</strong></div>

						</li>";


					// Now load the exercise questions
					$_sql = 'select * from _app_topic_ex_q where topic_id = :topic_id and ex_id = :ex_id';
					$_d = array('topic_id' => $this->_topic_id, 'ex_id' => $_id);
					$_f = array('i', 'i');
					$_qus = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
					shuffle($_qus);
					$_exq_count = 0;
					if (!empty($_qus)){
						do {
							$_q_count = $_exq_count +1;
							$_question = $_qus[$_exq_count]['question'];
							$_answer = $_qus[$_exq_count]['answer'];
							$_id = $_qus[$_exq_count]['id'];
							$tmp .= "
							<li>
								<div class = 'label vm ml5'>Q".$_q_count."</div>
								<div class = 'question'>".$_question."</div>
								<div class = 'eye vm'><div class = 'answer hidden' id = 'ans".$_id."' >".$_answer."</div><img class = 'answer' id = 'eye".$_id."' src = '_stdlib/_images/_icons/answer.png' /></div>
							</li>";
							$_exq_count++;
						} while ($_exq_count < $_num_qs && !empty($_qus[$_q_count]['id']));
					}
					$tmp .= "</ul>";
					$_ex_count++;
				}
			}
			return $tmp;

	}

	private function _build_examples(){
		$_sql = 'select * from _app_topic_examples where topic_id = :topic_id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);

		$tmp = "
			<script>
				$(document).ready(function(){
					$(document).on('click', '.open', function(){
						var id = $(this).attr('id').substring(1);
						if ($('#u'+id).hasClass('hidden')){
							$('#u'+id).removeClass('hidden');
							$('#ic'+id).removeClass('hidden');
							$('#io'+id).addClass('hidden');
						}else{
							$('#u'+id).addClass('hidden');
							$('#io'+id).removeClass('hidden');
							$('#ic'+id).addClass('hidden');
						}
					});
				});
			</script>";
		$_count = 1;
		if (!empty($_rows)){
			foreach ($_rows as $_row){
				$_id = $_row['id'];

				$_intro = $_row['intro'];
				$_method = $_row['method'];
				$_m_note = $_row['method_note'];
				$_ex = $_row['question'];
				$_q_note = $_row['question_note'];
				$_ans = $_row['answer'];
				$_a_note = $_row['answer_note'];

				//First build the table containing the example
				$tmp .= "<div id = 't".$_id."' class = 'green open point'>";
				$tmp .= "<div><img id = 'io".$_id."' src='".__s_lib_url__."_images/_icons/open.png' /></div>";
				$tmp .= "<div><img class = 'hidden' id = 'ic".$_id."' src='".__s_lib_url__."_images/_icons/close.png' /></div>";
				$tmp .= "<h3>Example $_count: $_intro</h3></div>
									<ul id = 'u".$_id."' class = 'topic_example hidden'>
										<li class = 'nb'><div class='label'> </div><div class='text'> </div><div class='text'><strong>Notes</strong></div></li>
										<li><div class='label'>Question:</div><div class='text'>$_ex</div><div class='note'>$_q_note</div></li>
										<li><div class='label'>Method:</div><div class='text'>$_method</div><div class='note'>$_m_note</div></li>
										<li><div class='label'>Answer:</div><div class='text'>$_ans</div><div class='note'>$_a_note</div></li>
									</ul>";
				$_count++;
			}
		}
		return $tmp;
	}

	public function _get_topic_id() { return $this->_topic_id; }
	public function _get_topic_route_id() { return $this->_topic_route_id; }
	public function _get_topic_title_bar() { return $this->_topic_title_bar; }
	public function _get_topic_tab_bar() { return $this->_topic_tab_bar; }
	public function _get_topic_title() { return $this->_topic_title; }
	public function _get_topic_intro() { return $this->_topic_intro; }
	public function _get_topic_title_bar_img() { return $this->_topic_title_bar_img; }
	public function _get_topic_ex_q_id() { return $this->_topic_ex_q_id; }
	public function _get_topic_ex_q() { return $this->_topic_ex_q; }
	public function _get_topic_ex_id() { return $this->_topic_ex_id; }


	public function _set_topic_id($_t) { $this->_topic_id = $_t; }
	public function _set_topic_route_id($_t) { $this->_topic_route_id = $_t; }
	public function _set_topic_title_bar($_t) { $this->_topic_title_bar = $_t; }
	public function _set_topic_tab_bar($_t) { $this->_topic_tab_bar = $_t; }
	public function _set_topic_title($_t) { $this->_topic_title = $_t; }
	public function _set_topic_intro($_t) { $this->_topic_intro = $_t; }
	public function _set_topic_title_bar_img($_t) { $this->_topic_title_bar_img = $_t; }
	public function _set_topic_ex_q_id($_t) { $this->_topic_ex_q_id = $_t; }
	public function _set_topic_ex_q($_t) { $this->_topic_ex_q = $_t; }
	public function _set_topic_ex_id($_t) { $this->_topic_ex_id = $_t; }
}
?>