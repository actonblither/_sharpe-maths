<?php

class _puzzle{
	private $_dbh;
	private $_id;
	private $_topic_id;
	private $_title;
	private $_problem;
	private $_hint;
	private $_solution;
	private $_explanation;
	private $_title_icon;
	private $_pz_page_title;
	private $_show_title = false;
	private $_make_pz_tab = false;

	private $_icon_path = __s_app_folder__.'_images/_icons/';
	private $_icon_url =  __s_app_url__.'_images/_icons/';

	public function __construct($_tid = null){
		if (is_logged_in()){
			$this->_make_pz_tab = true;
		}
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
	}

	public function _fetch_topic_puzzles(){
		$this->_set_pz_page_title('Topic puzzle page');
		$_sql = 'select p.* from _app_puzzles p left join _app_puzzle_topic_link pt on pt.puzzle_id = p.id where p.display = :display and p.archived = :archived and pt.topic_id = :topic_id order by p.difficulty';
		$_d = array('display' => 1, 'archived' => 0, 'topic_id' => $this->_topic_id);
		$_f = array('i', 'i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($_rows) || is_logged_in()){$this->_make_pz_tab = true;}
		return $this->_build_puzzle_page($_rows);
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

	private function _build_puzzle_page($_rows){
		$tmp = "";
		if ($this->_show_title){$tmp .= $this->_build_title_bar();}

		$tmp .= "<ul id = 'puzzle_page' class = 'puzzle'>";
			if (!empty($_rows)){
				foreach ($_rows as $_r){
					$this->_id = $_r['id'];
					$this->_title = $_r['title'];
					$this->_puzzle = $_r['puzzle'];
					$this->_hint = $_r['hint'];
					$this->_solution = $_r['solution'];
					$this->_explanation = $_r['explanation'];

					$tmp .= "<li class = 'row'>";
					$tmp .= "<ul class = 'puzzle_container w100pc'>";

					if (is_logged_in()){
						$tmp .= $this->_build_li_edit('puzzle', $this->_id, $this->_puzzle);
						$tmp .= $this->_build_hse_li_edit($this->_id);
					}else{
						$tmp .= $this->_build_li('puzzle', $this->_id, $this->_puzzle);
						$tmp .= $this->_build_hse_li($this->_id);
					}
					$tmp .= "</ul>";

					$tmp .= "</li>";
				}
			}
		$tmp .= "</ul>";
		return $tmp;
	}

	private function _build_title_bar(){
		$_tb = new _title_bar();
		$_tb->_set_img('puzzle32.png');
		$_tb->_set_title($this->_get_pz_page_title());
		return $_tb->_build_title_bar();
	}

	private function _build_hse_li($_id){
		$tmp = "<li>
							<div class = 'w32 ml50 mr10'>".$this->_fetch_icon('hint', 'hc', $_id)."</div>
							<div class = 'w32 mr10'>".$this->_fetch_icon('solution', 'sc', $_id)."</div>
							<div class = 'w32 mr10'>".$this->_fetch_icon('explanation', 'ec', $_id)."</div>
							<div class = 'hidden mr10 cwrap vm' id = 'hc".$_id."'><span class = 'h4'>Hint: &nbsp;</span>".$this->_hint."</div>
							<div class = 'hidden mr10 vm' id = 'sc".$_id."'><span class = 'h4'>Solution: &nbsp;</span>".$this->_solution."</div>
							<div class = 'hidden mr10 vm' id = 'ec".$_id."'><span class = 'h4'>Explanation: &nbsp;</span>".$this->_explanation."</div>
						</li>";
		return $tmp;
	}

	private function _build_hse_li_edit($_id){
		$this->_hint = $this->_build_textarea('_app_puzzles', 'hint', $this->_hint, $_id);
		$this->_solution = $this->_build_textarea('_app_puzzles', 'solution', $this->_solution, $_id);
		$this->_explanation = $this->_build_textarea('_app_puzzles', 'explanation', $this->_explanation, $_id);
		$tmp = "
	<li>
		<div class = 'w100pc mr10' id = 'hc".$_id."'><span class = 'h4'>Hint: &nbsp;</span>".$this->_hint."</div>
	</li>
	<li>
		<div class = 'w100pc mr10' id = 'sc".$_id."'><span class = 'h4'>Solution: &nbsp;</span>".$this->_solution."</div>
	</li>
	<li>
		<div class = 'w100pc mr10' id = 'ec".$_id."'><span class = 'h4'>Explanation: &nbsp;</span>".$this->_explanation."</div>
	</li>";
		return $tmp;
	}

	private function _build_li($_pr, $_id, $_txt){
		$tmp = "<li id = '".$_pr.$_id."' class = 'row'>
							<div class = 'w50 c ico_bin'>".$this->_fetch_icon($_pr, null, $_id)."</div>
							<div class = 'w100pc c text mr10'>".$_txt."</div>
						</li>";
		return $tmp;
}

private function _build_li_edit($_pr, $_id, $_txt){
	$_txt = $this->_build_textarea('_app_puzzles', 'puzzle', $_txt, $_id);

	$_topic_sel_el = $this->_build_select();

	$tmp =
	"<li>
			<span class = 'h4 mr20'>Puzzle:&nbsp;".$this->_title."</span>Topic link: ".$_topic_sel_el."
	</li>
	<li>".$_txt."</li>";
	return $tmp;
}

private function _build_select(){
	$_params = array(
		'el_type' => 'multiple',
		'el_name' => 'topic_id',
		'el_value' => $this->_topic_id,
		'db_display_field' => 'title',
		'db_tbl_sel' => '_app_topic',
		'db_tbl' => '_app_puzzle',
		'db_link_tbl' => '_app_puzzle_topic_link',
		'el_data_id' => $this->_id,
		'el_data_db_tbl' => '_app_puzzle_topic_link',
		'db_link_tbl_field_1' => 'topic_id',
		'db_link_tbl_field_2' => 'puzzle_id'
	);
	$_topic_sel = new _select($_params);
	return $_topic_sel->_build_select();
}

private function _build_textarea($_db_tbl, $_field_name, $_field_value, $_data_id){
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


	private function _fetch_icon($_pr, $_pre, $_id){
		$_ico = $_pr."32.png";
		if ($_pr == 'puzzle'){$_t = 'Puzzle problem'; $_reveal = ''; $_point = '';}
		if ($_pr == 'hint'){$_t = 'Click here for a hint.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'solution'){$_t = 'Click here for the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'explanation'){$_t = 'Click here for an explanation of the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		$_src = $this->_icon_url.$_ico;
		$tmp = "<img src = '".$_src."' class = '".$_reveal."w32 ttip".$_point."' title = '".$_t."' data-id = '".$_id."' data-text_div = '".$_pre."' />";
		return $tmp;
	}


	public function _get_dbh() { return $this->_dbh; }
	public function _get_id() { return $this->_id; }
	public function _get_title() { return $this->_title; }
	public function _get_problem() { return $this->_problem; }
	public function _get_hint() { return $this->_hint; }
	public function _get_solution() { return $this->_solution; }
	public function _get_explanation() { return $this->_explanation; }
	public function _get_pz_page_title() { return $this->_pz_page_title; }
	public function _get_show_title() { return $this->_show_title; }



	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_id($_t) { $this->_id = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }
	public function _set_problem($_t) { $this->_problem = $_t; }
	public function _set_hint($_t) { $this->_hint = $_t; }
	public function _set_solution($_t) { $this->_solution = $_t; }
	public function _set_explanation($_t) { $this->_explanation = $_t; }
	public function _set_pz_page_title($_t) { $this->_pz_page_title = $_t; }
	public function _set_show_title($_t) { $this->_show_title = $_t; }
	public function _set_make_pz_tab($_t) { $this->_make_pz_tab = $_t; }
	public function _get_make_pz_tab() { return $this->_make_pz_tab; }

}


